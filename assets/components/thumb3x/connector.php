<?php

require_once dirname(__FILE__,4).'/config.core.php';
require_once MODX_CORE_PATH.'config/'.MODX_CONFIG_KEY.'.inc.php';
require_once MODX_CONNECTORS_PATH.'index.php';


$Thumb3x = $modx->services->get('Thumb3x');
//$modx->lexicon->load('thumb3x:default');

// handle request
$path = $modx->getOption(
    'processorsPath',
    $Thumb3x->config,
    $modx->getOption('core_path') . 'components/thumb3x/' . 'src/Processors/'
);
$modx->getRequest();

$request = $modx->request;
$request->handleRequest([
    'processors_path' => $path,
    'location' => '',
]);