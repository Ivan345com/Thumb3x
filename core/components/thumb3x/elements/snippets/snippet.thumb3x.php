<?php
/**
 * Сниппет Thumb3x
 * @var modX $modx
 * @var array $scriptProperties
 */

$corePath = MODX_CORE_PATH . 'components/thumb3x/';

require_once $corePath . 'vendor/autoload.php';
if (!class_exists('Thumb3x\\Thumb3xHandler')) {
    require_once $corePath . 'Thumb3xHandler.php';
}

$modx->addPackage('Thumb3x\Model', $corePath . 'src/', null, 'Thumb3x\\');

try {
    $handler = new \Thumb3x\Thumb3xHandler($modx);
} catch (\Throwable $e) {
    $modx->log(xPDO::LOG_LEVEL_ERROR, '[Thumb3x] Не удалось создать экземпляр обработчика: ' . $e->getMessage());
    return '';
}

$input = $modx->getOption('input', $scriptProperties, '');
if (empty($input)) {
    // Пустой input - обычная ситуация (например, пустой TV): без записи в журнал
    return '';
}


// Получаем параметр &options. Он может быть строкой (от MODX) или массивом (от Fenom).
$options = $modx->getOption('options', $scriptProperties, []);

// Если это строка, разбираем ее в массив. Если это уже массив, ничего не делаем.
if (is_string($options)) {
    parse_str($options, $options);
}


$defaultSourceId = (int)$modx->getOption('thumb3x.source_id', null, 1);
$sourceId = (int)$modx->getOption('sourceId', $scriptProperties, $defaultSourceId);

$overrides = [];
if ($quality = $modx->getOption('quality', $scriptProperties, null)) {
    $overrides['thumbnail_quality'] = (int)$quality;
}
if ($format = $modx->getOption('format', $scriptProperties, null)) {
    $overrides['thumbnail_type'] = $format;
}
if ($watermark = $modx->getOption('watermark', $scriptProperties, null)) {
    $overrides['watermark_path'] = $watermark;
}

// Передаем все в getUrl, который сам обработает ошибки
return $handler->getUrl($input, $options, $sourceId, $overrides);