<?php

/**
 * Заполняет пустые файлы в установленных папках компонента до обновления.
 *
 * Валидатор выполняется до копирования файлов пакета. Перед копированием MODX архивирует
 * уже установленные файлы компонента (шаг "Attempting to preserve files"). Если вместо
 * ZipArchive работает PclZip (нет расширения zip или включена настройка archive_with),
 * на PHP 8 он падает на любом пустом файле:
 * "fread(): Argument #2 ($length) must be greater than 0" - и обновление не проходит.
 * В версиях до 3.1.1 пустыми были assets/components/thumb3x/css/index.html и js/index.html.
 *
 * Всегда возвращает true: валидатор не должен мешать установке.
 *
 * @var \xPDO\Transport\xPDOTransport $transport
 * @var \xPDO\Om\xPDOObject $object
 * @var array $options
 */

$action = $options[\xPDO\Transport\xPDOTransport::PACKAGE_ACTION] ?? null;
if ($action !== \xPDO\Transport\xPDOTransport::ACTION_INSTALL && $action !== \xPDO\Transport\xPDOTransport::ACTION_UPGRADE) {
    return true;
}
// xPDO вызывает валидатор и для вложенных объектов (сниппет и т.п.) - работаем один раз, для категории
if (!($object instanceof \MODX\Revolution\modCategory)) {
    return true;
}

$modx = $transport->xpdo;
$filled = 0;
$failed = [];

foreach ([MODX_CORE_PATH . 'components/thumb3x/', MODX_ASSETS_PATH . 'components/thumb3x/'] as $dir) {
    if (!is_dir($dir)) {
        continue;
    }
    try {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS)
        );
        foreach ($iterator as $file) {
            /** @var \SplFileInfo $file */
            if ($file->isLink() || !$file->isFile() || $file->getSize() !== 0) {
                continue;
            }
            // Содержимое выбираем так, чтобы файл остался рабочим: пустой PHP-файл должен
            // остаться PHP без вывода, HTML-заглушка - пустой страницей
            $ext = strtolower($file->getExtension());
            if ($ext === 'php') {
                $content = "<?php\n";
            } elseif ($ext === 'html' || $ext === 'htm') {
                $content = "<!DOCTYPE html><title></title>\n";
            } else {
                $content = "\n";
            }
            if (@file_put_contents($file->getPathname(), $content) !== false) {
                $filled++;
            } else {
                $failed[] = $file->getPathname();
            }
        }
    } catch (\Throwable $e) {
        $modx->log(\xPDO\xPDO::LOG_LEVEL_WARN, '[Thumb3x] Could not check for empty files in ' . $dir . ': ' . $e->getMessage());
    }
}

if ($filled) {
    $modx->log(\xPDO\xPDO::LOG_LEVEL_INFO, '[Thumb3x] Filled ' . $filled . ' empty file(s) of the previous version before update');
}
if ($failed) {
    $modx->log(
        \xPDO\xPDO::LOG_LEVEL_WARN,
        '[Thumb3x] Empty files could not be filled (not writable). If the update fails with "fread(): Argument #2 ($length) must be greater than 0", '
        . 'put any text into them or delete them: ' . implode(', ', $failed)
    );
}

return true;
