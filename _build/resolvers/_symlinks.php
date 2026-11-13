<?php

/** @var xPDOTransport $transport */
/** @var array $options */
/** @var modX $modx */
if ($transport->xpdo) {
    $modx = $transport->xpdo;

    $dev = MODX_BASE_PATH . 'Extras/Thumb3x/';
    /** @var xPDOCacheManager $cache */
    $cache = $modx->getCacheManager();
    if (file_exists($dev) && $cache) {
        if (!is_link($dev . 'assets/components/thumb3x')) {
            $cache->deleteTree(
                $dev . 'assets/components/thumb3x/',
                ['deleteTop' => true, 'skipDirs' => false, 'extensions' => []]
            );
            symlink(MODX_ASSETS_PATH . 'components/thumb3x/', $dev . 'assets/components/thumb3x');
        }
        if (!is_link($dev . 'core/components/thumb3x')) {
            $cache->deleteTree(
                $dev . 'core/components/thumb3x/',
                ['deleteTop' => true, 'skipDirs' => false, 'extensions' => []]
            );
            symlink(MODX_CORE_PATH . 'components/thumb3x/', $dev . 'core/components/thumb3x');
        }
    }
}

return true;
