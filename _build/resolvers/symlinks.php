<?php
/** @var xPDOTransport $transport */
/** @var array $options */
/** @var modX $modx */
if ($transport->xpdo) {
    $modx =& $transport->xpdo;

    $dev = MODX_BASE_PATH . 'Extras/autoD/';
    /** @var xPDOCacheManager $cache */
    $cache = $modx->getCacheManager();
    if (file_exists($dev) && $cache) {
        if (!is_link($dev . 'assets/components/autod')) {
            $cache->deleteTree(
                $dev . 'assets/components/autod/',
                ['deleteTop' => true, 'skipDirs' => false, 'extensions' => []]
            );
            symlink(MODX_ASSETS_PATH . 'components/autod/', $dev . 'assets/components/autod');
        }
        if (!is_link($dev . 'core/components/autod')) {
            $cache->deleteTree(
                $dev . 'core/components/autod/',
                ['deleteTop' => true, 'skipDirs' => false, 'extensions' => []]
            );
            symlink(MODX_CORE_PATH . 'components/autod/', $dev . 'core/components/autod');
        }
    }
}

return true;