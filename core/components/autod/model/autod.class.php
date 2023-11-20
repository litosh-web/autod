<?php

class autoD
{
    /** @var modX $modx */
    public $modx;
    public $controller;


    /**
     * @param modX $modx
     * @param array $config
     */
    function __construct(modX &$modx, array $config = [])
    {
        $this->modx =& $modx;
        $corePath = MODX_CORE_PATH . 'components/autod/';
        $assetsUrl = MODX_ASSETS_URL . 'components/autod/';

        $this->config = array_merge([
            'assetsUrl' => $assetsUrl,
            'actionUrl' => $assetsUrl . 'action.php',
            'cssUrl' => $assetsUrl . 'css/',
            'jsUrl' => $assetsUrl . 'js/',
            'imagesUrl' => $assetsUrl . 'images/',
            'connectorUrl' => $assetsUrl . 'connector.php',

            'controllersPath' => $corePath . 'controllers/',
            'corePath' => $corePath,
            'modelPath' => $corePath . 'model/',
            'chunksPath' => $corePath . 'elements/chunks/',
            'templatesPath' => $corePath . 'elements/templates/',
            'chunkSuffix' => '.chunk.tpl',
            'snippetsPath' => $corePath . 'elements/snippets/',
            'processorsPath' => $corePath . 'processors/'
        ], $config);

        $this->modx->addPackage('autod', $this->config['modelPath']);
        $this->modx->lexicon->load('autod:default');
    }

    public function loadJsCssWeb($objectName = 'autod')
    {

        //CSS
        $this->modx->regClientCSS($this->config['cssUrl'] . 'web/default.css');

        if ($js = trim($this->config['frontend_js'])) {
            if (preg_match('/\.js/i', $js)) {
                $this->modx->regClientScript(str_replace('[[+assetsUrl]]', $this->config['assetsUrl'], $js));
            }
        }

        $config = $this->modx->toJSON(array(
            'assetsUrl' => $this->config['assetsUrl'],
            'actionUrl' => str_replace('[[+assetsUrl]]', $this->config['assetsUrl'], $this->config['actionUrl']),
            'cssUrl' => $this->config['assetsUrl'] . 'css/',
            'jsUrl' => $this->config['assetsUrl'] . 'js/',
        ));

        $objectName = trim($objectName);
        $this->modx->regClientScript(
            "<script type=\"text/javascript\">{$objectName}.initialize({$config});</script>", true
        );
    }

    public function loadController($controller)
    {
        $classPath = $this->config['controllersPath'] . 'web/' . $controller . '.php';
        $className = $controller . 'autoDController';

        if (file_exists($classPath)) {
            if (!class_exists($className)) {
                $className = require_once $classPath;
            }
            if (class_exists($className)) {
                $this->controller = new $className($this, $this->config);
            } else {
                $this->modx->log(modX::LOG_LEVEL_ERROR, '[autoD] Could not load controller: ' . $className . ' at ' . $classPath);
            }
        } else {
            $this->modx->log(modX::LOG_LEVEL_ERROR, '[autoD] Could not load controller file: ' . $classPath);
        }
        return $this->controller;
    }

}