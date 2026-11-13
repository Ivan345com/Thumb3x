<?php

namespace Thumb3x;

use MODX\Revolution\modX;
use MODX\Revolution\Sources\modMediaSource;

/**
 * Папка кэша Thumb3x: путь из настройки thumb3x.cache_path и проверка,
 * что её содержимое можно безопасно удалять.
 */
class CachePath
{
    public const DEFAULT_PATH = 'assets/components/thumb3x/cache/';

    /**
     * Путь к папке кэша относительно корня сайта, со слэшем в конце.
     * Пустая настройка означает путь по умолчанию: иначе кэшем стал бы корень сайта.
     */
    public static function relative(modX $modx): string
    {
        $value = trim((string)$modx->getOption('thumb3x.cache_path', null, self::DEFAULT_PATH));
        $value = trim($value, '/');
        if ($value === '') {
            $value = trim(self::DEFAULT_PATH, '/');
        }

        return $value . '/';
    }

    /**
     * Абсолютный путь к папке кэша, со слэшем в конце.
     */
    public static function absolute(modX $modx): string
    {
        return MODX_BASE_PATH . self::relative($modx);
    }

    /**
     * Можно ли удалять содержимое папки кэша.
     * Нельзя, если путь выходит за пределы сайта через "..", совпадает с корнем сайта,
     * системными папками MODX, папкой компонента или корнем медиаисточника либо содержит их внутри себя.
     * Причина отказа пишется в журнал ошибок.
     */
    public static function isSafeToClean(modX $modx): bool
    {
        $relative = self::relative($modx);
        $cachePath = self::absolute($modx);

        if (strpos($relative, "\0") !== false || strpos($relative, '\\') !== false) {
            return self::deny($modx, $relative, 'недопустимые символы в пути');
        }
        foreach (explode('/', rtrim($relative, '/')) as $segment) {
            if ($segment === '' || $segment === '.' || $segment === '..') {
                return self::deny($modx, $relative, 'путь содержит ".", ".." или пустой сегмент');
            }
        }

        $protected = [
            MODX_BASE_PATH,
            MODX_CORE_PATH,
            MODX_CORE_PATH . 'components/',
            MODX_CORE_PATH . 'components/thumb3x/',
            MODX_ASSETS_PATH,
            MODX_ASSETS_PATH . 'components/',
            MODX_ASSETS_PATH . 'components/thumb3x/',
            MODX_MANAGER_PATH,
            MODX_CONNECTORS_PATH,
        ];
        foreach (self::mediaSourcePaths($modx) as $path) {
            $protected[] = $path;
        }

        $cacheForms = self::forms($cachePath);
        foreach ($protected as $path) {
            foreach (self::forms($path) as $protectedForm) {
                foreach ($cacheForms as $cacheForm) {
                    // Папка кэша совпадает с защищённой папкой или является её родителем
                    if (strpos($protectedForm, $cacheForm) === 0) {
                        return self::deny($modx, $relative, 'совпадает с ' . $path . ' или содержит её');
                    }
                }
            }
        }

        return true;
    }

    /**
     * Путь на диске к файлу по URL из записи в БД (поле cache_path).
     * Сейчас URL имеет вид "<папка кэша>/<файл>". В старых записях сайтов из подпапки
     * перед ним стоит подпапка ("sub/assets/..."), её отбрасываем.
     */
    public static function fileFromUrl(modX $modx, string $url): string
    {
        $url = ltrim($url, '/');
        $relative = self::relative($modx);

        $subfolder = trim(MODX_BASE_URL, '/');
        if ($subfolder !== '' && strpos($url, $relative) !== 0 && strpos($url, $subfolder . '/') === 0) {
            $url = substr($url, strlen($subfolder) + 1);
        }

        return MODX_BASE_PATH . $url;
    }

    /**
     * Удаляет опустевшие папки от папки файла вверх, но не выше корня кэша.
     * Схема кэша бывает вложенной (например ab/cd/<hash>.webp или <путь исходника>/<hash>.webp).
     */
    public static function removeEmptyParents(string $cachePath, string $file): void
    {
        $root = rtrim($cachePath, '/');
        $dir = dirname($file);
        while (
            is_dir($dir)
            && strlen($dir) > strlen($root)
            && strpos($dir, $root . '/') === 0
            && count(scandir($dir)) == 2
        ) {
            if (!@rmdir($dir)) {
                break;
            }
            $dir = dirname($dir);
        }
    }

    /**
     * Лежит ли файл внутри папки кэша (с учётом символических ссылок).
     */
    public static function contains(string $cachePath, string $file): bool
    {
        $root = realpath($cachePath);
        $real = realpath($file);
        if ($root === false || $real === false) {
            return false;
        }

        return strpos($real, rtrim($root, '/') . '/') === 0;
    }

    /**
     * Корневые папки всех медиаисточников, которые существуют на диске.
     * @return string[]
     */
    protected static function mediaSourcePaths(modX $modx): array
    {
        $paths = [];
        try {
            /** @var modMediaSource $source */
            foreach ($modx->getIterator(modMediaSource::class) as $source) {
                try {
                    $source->initialize();
                    $path = (string)$source->getBasePath();
                    if ($path !== '' && is_dir($path)) {
                        $paths[] = $path;
                    }
                } catch (\Throwable $e) {
                    // Источник, который не удалось инициализировать, не мешает проверке остальных
                }
            }
        } catch (\Throwable $e) {
            $modx->log(modX::LOG_LEVEL_ERROR, '[Thumb3x] Не удалось получить список медиаисточников: ' . $e->getMessage());
        }

        return $paths;
    }

    /**
     * Варианты записи пути для сравнения: как есть и через realpath, оба со слэшем в конце.
     * @return string[]
     */
    protected static function forms(string $path): array
    {
        $forms = [rtrim($path, '/') . '/'];
        $real = realpath($path);
        if ($real !== false) {
            $forms[] = rtrim($real, '/') . '/';
        }

        return array_unique($forms);
    }

    protected static function deny(modX $modx, string $relative, string $reason): bool
    {
        $modx->log(
            modX::LOG_LEVEL_ERROR,
            '[Thumb3x] Очистка кэша отменена: папка "' . $relative . '" (thumb3x.cache_path) не прошла проверку - ' . $reason
        );

        return false;
    }
}
