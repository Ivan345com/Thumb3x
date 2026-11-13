<?php

namespace Thumb3x;

use MODX\Revolution\modX;
use League\Glide\ServerFactory;
use League\Flysystem\Local\LocalFilesystemAdapter;
use League\Flysystem\Filesystem;
use League\Flysystem\UnixVisibility\PortableVisibilityConverter;
use MODX\Revolution\Sources\modMediaSource;
use League\Glide\Server;
use Thumb3x\Model\Thumb3xFile;

class Thumb3xHandler
{
    public $modx;
    protected $instances = []; // Кэш для экземпляров Glide

    /** @var array Сообщения, уже записанные в журнал за этот запрос (сниппет создаёт обработчик на каждый вызов) */
    protected static $logged = [];

    public function __construct(modX $modx)
    {
        $this->modx = $modx;
    }

    /**
     * Получает или создает экземпляр Glide и его конфигурацию
     * @param int $sourceId
     * @param string|null $watermarkPath
     * @return array|null
     */
    protected function getInstance(int $sourceId, ?string $watermarkPath = null): ?array
    {
        // Ключ для кэша экземпляров теперь зависит и от водяного знака
        $instanceKey = $sourceId . '_' . md5($watermarkPath ?? 'no_watermark');
        if (isset($this->instances[$instanceKey])) {
            return $this->instances[$instanceKey];
        }

        try {
            $sourceInfo = $this->getMediaSourceInfo($sourceId);
            
            // Путь к кэшу из настроек (пустая настройка - путь по умолчанию, а не корень сайта)
            $cachePathRelative = CachePath::relative($this->modx);
            $cachePath = MODX_BASE_PATH . $cachePathRelative;
            // URL кэша - от корня сайта, без MODX_BASE_URL. Glide всё равно обрезает начальный "/",
            // и у сайта в подпапке получалось "sub/assets/...", что при <base href> вело в /sub/sub/.
            // Для сайта в корне URL не меняется.
            $cacheUrl = $cachePathRelative;

            if (!$sourceInfo || is_null($sourceInfo['path'])) {
                $this->logOnce('Не удалось получить путь из источника файлов с ID ' . $sourceId);
                return null;
            }
            $sourcePath = $sourceInfo['path'];

            $fileRights = $this->getRights('thumb3x.cache_file_rights', 0644);
            $dirRights = $this->getRights('thumb3x.cache_dir_rights', 0755);

            if (!is_dir($sourcePath) || !is_readable($sourcePath)) {
                $this->logOnce('Папка источника файлов с ID ' . $sourceId . ' не найдена или недоступна для чтения: ' . $sourcePath);
                return null;
            }
            if (!is_dir($cachePath) && !@mkdir($cachePath, $dirRights, true) && !is_dir($cachePath)) {
                $this->logOnce('Не удалось создать папку кэша: ' . $cachePath);
                return null;
            }
            if (!is_writable($cachePath)) {
                $this->logOnce('Папка кэша недоступна для записи: ' . $cachePath);
                return null;
            }

            $driver = $this->modx->getOption('thumb3x.driver', null, 'gd');

            $visibility = PortableVisibilityConverter::fromArray(['file' => $fileRights, 'dir' => $dirRights]);
            $cacheAdapter = new LocalFilesystemAdapter($cachePath, $visibility);
            $sourceAdapter = new LocalFilesystemAdapter($sourcePath);
            $cacheConfig = ['visibility' => 'public', 'directory_visibility' => 'public'];

            $glideConfig = [
                'source' => new Filesystem($sourceAdapter),
                'cache' => new Filesystem($cacheAdapter, $cacheConfig),
                'base_url' => $cacheUrl,
                'cache_with_file_extensions' => true,
                'driver' => $driver,
            ];

            // Дополнительные медиаисточники (не thumb3x.source_id) получают свою папку в кэше и свой хэш:
            // иначе одинаковые относительные пути разных источников давали одну и ту же картинку.
            // Для основного источника всё как в прежних версиях - уже созданные картинки не меняются.
            $hashPrefix = $this->getHashPrefix($sourceId);
            if ($hashPrefix !== '') {
                $glideConfig['cache_path_prefix'] = '_source' . $sourceId;
            }

            // Схема именования файлов в кэше.
            // 'source' (по умолчанию) - штатное поведение Glide: путь к исходнику попадает в URL кэша.
            // 'hashed' / 'flat'      - имя файла строится только из хэша, путь к исходнику не раскрывается.
            // Любое неизвестное значение трактуется как 'source', чтобы настройка не могла сломать сайт.
            $cacheMode = strtolower(trim((string)$this->modx->getOption('thumb3x.cache_path_mode', null, 'source')));

            if (in_array($cacheMode, ['hashed', 'flat'], true)) {
                // Замыкание не должно быть static: Glide делает Closure::bind($callable, $server),
                // а привязать объект к static-замыканию нельзя - будет "Invalid cache path callable".
                $glideConfig['cache_path_callable'] = function (string $path, array $params) use ($cacheMode, $hashPrefix) {
                    $ext = strtolower((string)($params['fm'] ?? pathinfo($path, PATHINFO_EXTENSION)));
                    $ext = $ext === 'pjpg' ? 'jpg' : $ext;

                    // Та же формула, что и $hash в getUrl(), - имя файла совпадает с записью в БД.
                    $hash = sha1($hashPrefix . $path . http_build_query($params));
                    $name = $hash . ($ext !== '' ? '.' . $ext : '');

                    return $cacheMode === 'hashed'
                        ? substr($hash, 0, 2) . '/' . substr($hash, 2, 2) . '/' . $name
                        : $name;
                };
            }

            $hasWatermarks = false;
            if (!empty($watermarkPath)) {
                $fullWatermarkPath = MODX_BASE_PATH . ltrim($watermarkPath, '/');
                if (is_file($fullWatermarkPath)) {
                    $watermarkDir = dirname($fullWatermarkPath);
                    $watermarkAdapter = new LocalFilesystemAdapter($watermarkDir);
                    $glideConfig['watermarks'] = new Filesystem($watermarkAdapter);
                    $hasWatermarks = true;
                } else {
                    $this->modx->log(modX::LOG_LEVEL_ERROR, '[Thumb3x] Файл водяного знака не найден: ' . $fullWatermarkPath);
                }
            }

            $glideServer = ServerFactory::create($glideConfig);

            return $this->instances[$instanceKey] = [
                'server' => $glideServer,
                'cache_path' => $cachePath,
                'has_watermarks' => $hasWatermarks,
                'source_info' => $sourceInfo,
            ];

        } catch (\Throwable $e) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, '[Thumb3x] Критическая ошибка при инициализации Glide: ' . $e->getMessage());
            return null;
        }
    }

    protected function getMediaSourceInfo(int $sourceId): ?array
    {
        $source = $this->modx->getObject(modMediaSource::class, $sourceId);
        if (!$source) {
            return null;
        }
        $source->initialize();
        $properties = $source->getProperties();
        return [
            'path' => $source->getBasePath(), 'url' => $source->getBaseUrl(),
            'image_extensions' => $properties['imageExtensions']['value'] ?? 'jpg,jpeg,png,gif,svg,webp',
            'thumbnail_quality' => (int)($properties['thumbnailQuality']['value'] ?? 90),
            'thumbnail_type' => $properties['thumbnailType']['value'] ?? 'jpg',
        ];
    }

    /**
     * URL картинки в кэше. При любой ошибке - пустая строка (причина пишется в журнал):
     * текст ошибки в src браузер запрашивал как адрес, и MODX отдавал 404 на каждую битую картинку.
     */
    public function getUrl(string $path, array $params = [], int $sourceId = 1, array $overrides = []): string
    {
        $watermarkPath = $overrides['watermark_path'] ?? null;
        $instance = $this->getInstance($sourceId, $watermarkPath);
        if (!$instance) {
            // причина уже записана в журнал в getInstance()
            return '';
        }

        /** @var Server $glide */
        $glide = $instance['server'];
        $cachePath = $instance['cache_path'];
        $hasWatermarks = $instance['has_watermarks'];
        $sourceInfo = $instance['source_info'];

        try {
            $allowedExtsList = $overrides['image_extensions'] ?? $sourceInfo['image_extensions'];
            $allowedExts = explode(',', strtolower(str_replace(' ', '', $allowedExtsList)));
            $fileExt = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            if (!empty($fileExt) && !in_array($fileExt, $allowedExts)) {
                $this->logOnce('Недопустимый тип файла "' . $fileExt . '": ' . $path . ' (разрешены: ' . implode(',', $allowedExts) . ')', modX::LOG_LEVEL_WARN);
                return '';
            }

            $defaultParams = [
                'q' => $overrides['thumbnail_quality'] ?? $sourceInfo['thumbnail_quality'],
                'fm' => strtolower($overrides['thumbnail_type'] ?? $sourceInfo['thumbnail_type']),
            ];
            $finalParams = array_merge($defaultParams, $params);
            $finalParams = $this->sanitizeFormat($finalParams);

            if ($watermarkPath && $hasWatermarks) {
                if (isset($finalParams['mark'])) {
                    // все хорошо, параметр уже есть в &options
                } else {
                    $finalParams['mark'] = basename($watermarkPath);
                }
            }

            $hash = sha1($this->getHashPrefix($sourceId) . $path . http_build_query($finalParams));
            /** @var Thumb3xFile|null $logEntry */
            $logEntry = $this->modx->getObject(Thumb3xFile::class, ['hash' => $hash]);

            if ($logEntry) {
                $cachedUrl = (string)$logEntry->get('cache_path');
                // Запись в БД ещё не значит, что файл на месте: несколько записей могут указывать
                // на один файл (и он удаляется вместе с любой из них), кэш могли очистить вручную
                // или не перенести вместе с сайтом. Тогда создаём файл заново и обновляем запись.
                if ($this->cachedFileExists($cachedUrl, $glide->getBaseUrl(), $cachePath)) {
                    // Дату показа обновляем не чаще раза в сутки, чтобы не писать в БД на каждый показ
                    if (strpos((string)$logEntry->get('accessedon'), date('Y-m-d')) !== 0) {
                        $logEntry->set('accessedon', date('Y-m-d H:i:s'));
                        $logEntry->save();
                    }
                    return $cachedUrl;
                }
            }

            if (!$glide->getSource()->fileExists($path)) {
                $this->logOnce('Исходный файл не найден: ' . $path . ' (источник ' . $sourceId . ')', modX::LOG_LEVEL_WARN);
                return '';
            }

            $cacheFile = $glide->makeImage($path, $finalParams);
            $fullCachePath = rtrim($cachePath, '/') . '/' . ltrim($cacheFile, '/');
            $baseUrl = rtrim($glide->getBaseUrl(), '/');
            $finalUrl = $baseUrl . '/' . ltrim($cacheFile, '/');

            if (!file_exists($fullCachePath)) {
                $this->logOnce('Не удалось создать файл в кэше: ' . $fullCachePath);
                return '';
            }

            $imageSize = getimagesize($fullCachePath);
            $now = date('Y-m-d H:i:s');
            $data = [
                'hash' => $hash, 'source_id' => $sourceId, 'source_path' => $path, 'cache_path' => $finalUrl, 'params' => $finalParams,
                'cache_filesize' => filesize($fullCachePath), 'cache_width' => $imageSize[0] ?? 0, 'cache_height' => $imageSize[1] ?? 0,
                'createdon' => $now, 'accessedon' => $now,
            ];

            if ($logEntry) {
                // Запись уже была (файл пропал) - обновляем её
                $logEntry->fromArray($data);
                $logEntry->save();
            } else {
                $this->insertEntry($data);
            }

            return $finalUrl;
        } catch (\Exception $e) {
            $this->logOnce('Ошибка генерации ' . $path . ': ' . $e->getMessage());
            return '';
        }
    }

    /**
     * Добавка к хэшу для медиаисточника. Для основного источника (thumb3x.source_id) - пустая строка,
     * чтобы хэши и имена файлов уже созданных картинок не менялись; для остальных - номер источника.
     */
    protected function getHashPrefix(int $sourceId): string
    {
        $default = (int)$this->modx->getOption('thumb3x.source_id', null, 1);
        if ($default <= 0) {
            $default = 1;
        }

        return $sourceId === $default ? '' : 'source' . $sourceId . ':';
    }

    /**
     * Добавляет запись о файле в кэше.
     * Одну и ту же новую картинку могут одновременно запросить несколько посетителей (например,
     * сразу после очистки кэша): все не находят запись и создают файл. Обычная вставка через xPDO
     * у всех, кроме первого, падает на уникальном hash и пишет в журнал ошибку на каждую попытку.
     * Поэтому вставка атомарная: если запись уже добавлена параллельным запросом, она обновляется.
     */
    protected function insertEntry(array $data): void
    {
        $table = $this->modx->getTableName(Thumb3xFile::class);
        $sql = "INSERT INTO {$table}
                (`hash`, `source_id`, `source_path`, `cache_path`, `params`, `cache_filesize`, `cache_width`, `cache_height`, `createdon`, `accessedon`)
                VALUES (:hash, :source_id, :source_path, :cache_path, :params, :cache_filesize, :cache_width, :cache_height, :createdon, :accessedon)
                ON DUPLICATE KEY UPDATE
                `source_id` = VALUES(`source_id`), `source_path` = VALUES(`source_path`), `cache_path` = VALUES(`cache_path`),
                `params` = VALUES(`params`), `cache_filesize` = VALUES(`cache_filesize`), `cache_width` = VALUES(`cache_width`),
                `cache_height` = VALUES(`cache_height`), `accessedon` = VALUES(`accessedon`)";

        // Ошибка записи в БД не должна ломать картинку: файл уже создан, URL отдаём в любом случае
        try {
            $stmt = $this->modx->prepare($sql);
            $ok = $stmt && $stmt->execute([
                ':hash' => $data['hash'],
                ':source_id' => (int)$data['source_id'],
                ':source_path' => (string)$data['source_path'],
                ':cache_path' => (string)$data['cache_path'],
                ':params' => $this->modx->toJSON($data['params']),
                ':cache_filesize' => (int)$data['cache_filesize'],
                ':cache_width' => (int)$data['cache_width'],
                ':cache_height' => (int)$data['cache_height'],
                ':createdon' => $data['createdon'],
                ':accessedon' => $data['accessedon'],
            ]);
            if (!$ok) {
                $error = $stmt ? $stmt->errorInfo() : $this->modx->errorInfo();
                $this->logOnce('Не удалось сохранить запись о файле в кэше: ' . ($error[2] ?? 'неизвестная ошибка'));
            }
        } catch (\Throwable $e) {
            $this->logOnce('Не удалось сохранить запись о файле в кэше: ' . $e->getMessage());
        }
    }

    /**
     * Годится ли URL из записи в БД: он должен вести в текущую папку кэша, и файл должен быть на месте.
     * Иначе (файл удалили, сменили thumb3x.cache_path, старая запись сайта из подпапки вида
     * "sub/assets/...") картинка создаётся заново и запись обновляется.
     */
    protected function cachedFileExists(string $cachedUrl, string $baseUrl, string $cachePath): bool
    {
        $url = ltrim($cachedUrl, '/');
        $base = trim($baseUrl, '/');
        if ($url === '' || $base === '' || strpos($url, $base . '/') !== 0) {
            return false;
        }

        return is_file(rtrim($cachePath, '/') . '/' . substr($url, strlen($base) + 1));
    }

    /**
     * Права из настройки в восьмеричном виде ("0644", "755"). Пустое или непонятное значение
     * даёт права по умолчанию: иначе octdec('') = 0 и файлы стали бы недоступны веб-серверу.
     */
    protected function getRights(string $key, int $default): int
    {
        $value = trim((string)$this->modx->getOption($key, null, ''));
        if (preg_match('/^[0-7]{3,4}$/', $value)) {
            return octdec($value);
        }
        if ($value !== '') {
            $this->logOnce('Настройка ' . $key . ' = "' . $value . '" не похожа на права в восьмеричном виде, используется ' . sprintf('%04o', $default));
        }

        return $default;
    }

    /**
     * Формат картинки (fm) - только графические форматы. Расширение файла в кэше берётся из fm как есть,
     * поэтому fm=php дал бы файл .php в папке кэша. Графические форматы (и пустое значение) не меняются,
     * чтобы хэши уже созданных картинок остались прежними; любой другой заменяется на jpg -
     * Glide и так кодирует неизвестный формат в JPEG, меняется только расширение.
     */
    protected function sanitizeFormat(array $params): array
    {
        if (!array_key_exists('fm', $params)) {
            return $params;
        }
        $fm = $params['fm'];
        if (is_scalar($fm) && ((string)$fm === '' || in_array(strtolower((string)$fm), $this->getImageFormats(), true))) {
            return $params;
        }
        $this->logOnce('Недопустимый формат fm "' . (is_scalar($fm) ? $fm : gettype($fm)) . '", используется jpg', modX::LOG_LEVEL_WARN);
        $params['fm'] = 'jpg';

        return $params;
    }

    /**
     * Все графические форматы, которые знает библиотека intervention/image (её использует Glide).
     * Берём из самой библиотеки, чтобы при её обновлении новые форматы подхватывались сами;
     * запасной список - на случай, если класс недоступен.
     * @return string[]
     */
    protected function getImageFormats(): array
    {
        static $formats = null;
        if ($formats === null) {
            $formats = ['jpg', 'jpeg', 'pjpg', 'pjpeg', 'webp', 'avif', 'bmp', 'gif', 'png', 'tif', 'tiff',
                'jp2', 'j2k', 'jp2k', 'jpf', 'jpm', 'jpg2', 'j2c', 'jpc', 'jpx', 'heic', 'heif'];
            if (enum_exists(\Intervention\Image\FileExtension::class)) {
                foreach (\Intervention\Image\FileExtension::cases() as $case) {
                    $formats[] = strtolower($case->value);
                }
                $formats = array_values(array_unique($formats));
            }
        }

        return $formats;
    }

    /**
     * Пишет сообщение в журнал один раз за запрос: на странице может быть много картинок.
     * Ошибки сервера - ERROR; ошибки в вызове (нет файла, не тот тип) - WARN: по умолчанию
     * MODX их не пишет (log_level = 1), чтобы отсутствующие картинки не раздували журнал.
     */
    protected function logOnce(string $message, int $level = modX::LOG_LEVEL_ERROR): void
    {
        if (isset(self::$logged[$message])) {
            return;
        }
        self::$logged[$message] = true;
        $this->modx->log($level, '[Thumb3x] ' . $message);
    }
}