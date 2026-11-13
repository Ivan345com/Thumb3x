<?php

/**
 * @var \MODX\Revolution\modX $modx
 * @var array $namespace
 */

// Load the classes
$modx->addPackage('Thumb3x\Model', $namespace['path'] . 'src/', null, 'Thumb3x\\');

$modx->services->add('Thumb3x', function ($c) use ($modx) {
    return new Thumb3x\Thumb3x($modx);
});
