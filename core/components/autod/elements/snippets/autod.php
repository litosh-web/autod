<?php
require_once $modx->getOption('autod_core_path', null, $modx->getOption('core_path') . 'components/autod/') . 'model/autod.class.php';
$autod = new autoD($modx, $scriptProperties);
if (!is_object($autod) || !($autod instanceof autoD)) return '';

$autod->loadJsCssWeb();

$controller = $autod->loadController('autoD');

$output = $controller->run($scriptProperties);
return $output;