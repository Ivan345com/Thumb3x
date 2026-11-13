<?php

namespace Thumb3x;

use MODX\Revolution\modX;

class Thumb3x
{
    /** @var modX $modx */
    public $modx;
    /** @var array $config */
    public $config = [];

    public function __construct(modX $modx, array $config = [])
    {
        $this->modx = $modx;
        $corePath = $this->modx->getOption('thumb3x.core_path', $config, $this->modx->getOption('core_path') . 'components/thumb3x/');
        $assetsUrl = $this->modx->getOption('thumb3x.assets_url', $config, $this->modx->getOption('assets_url') . 'components/thumb3x/');

        $this->config = array_merge([
            'corePath' => $corePath,
            'modelPath' => $corePath . 'src/Model/',
            'processorsPath' => $corePath . 'src/Processors/',
            'connectorUrl' => $assetsUrl . 'connector.php',
            'assetsUrl' => $assetsUrl,
            'jsUrl' => $assetsUrl . 'js/',
            'cssUrl' => $assetsUrl . 'css/',
        ], $config);
        $this->modx->lexicon->load('thumb3x:default');
    }
}