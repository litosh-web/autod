<?php
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php';
require_once MODX_CORE_PATH.'model/modx/modx.class.php';

$properties = array();

$modx = new modX();
$modx->initialize('web');
$modx->getService('error','error.modError', '', '');

$autod = $modx->getService('autod', 'autoD', $modx->getOption('autod_core_path', null,
        $modx->getOption('core_path') . 'components/autod/') . 'model/', $properties
);

$path = MODX_CORE_PATH . 'components/autod/processors/web/';

$response = $modx->runProcessor($_REQUEST['action'], $_REQUEST, array(
    'processors_path' => $path
));

$json = $modx->toJSON($response->response);

echo $json;

@session_write_close();
exit();