<?php

class autoDautoDController
{

    /** @var modX $modx */
    public $modx;
    protected $scriptProperties = array();

    function __construct(autoD & $autod, array $config = array())
    {
        $this->modx =& $autod->modx;
    }

    public function run($scriptProperties)
    {
        $this->setProperties($scriptProperties);
        return $this->process();
    }

    public function process()
    {
        $scriptProperties = $this->scriptProperties;
        $id = $this->getProperty('id');

        // pdoTools
        $fqn = $this->modx->getOption('pdoFetch.class', null, 'pdotools.pdofetch', true);
        $path = $this->modx->getOption('pdofetch_class_path', null, MODX_CORE_PATH . 'components/pdotools/model/', true);
        if ($pdoClass = $this->modx->loadClass($fqn, $path, false, true)) {
            $pdoFetch = new $pdoClass($this->modx, $scriptProperties);
        } else {
            return $this->modx->lexicon('autod_pdo_not_found');
        }

//        if (empty($id) || !$calc = $this->modx->getObject('autoDCalcs', $id)) {
//            return $this->modx->lexicon('autod_calc_not_found');
//        }
//
        $tpl = $this->modx->getOption('tpl', $scriptProperties, 'tpl.autoD');

        $output = $pdoFetch->getChunk($tpl, []);

        return $output;
    }

    public function setProperty($key, $value)
    {
        $this->scriptProperties[$key] = $value;
    }

    public function getProperty($key)
    {
        return $this->scriptProperties[$key];
    }

    public function setProperties($array)
    {
        foreach ($array as $k => $v) {
            $this->setProperty($k, $v);
        }
    }
}

return 'autoDautoDController';