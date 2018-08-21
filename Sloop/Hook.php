<?php

namespace Sloop;

class Hook {

    static private $_instance;

    private $_pluginList = array();

    static public function getInstance() {
        if (NULL == self::$_instance) {
            self::$_instance = new Tiri_Hook();
        }
        return self::$_instance;
    }

    static public function loadHookFile($file) {
        if (!file_exists($file)) return false;
        require_once $file;

    }

    public function addHook($hookPoint, $funcName) {
        $this->_pluginList[$hookPoint][] = $funcName;
    }

    /** 屏蔽掉某个hookPoint */
    public function shieldHook($hookPoint) {
        $this->_pluginList[$hookPoint] = array();
    }

    public function runHook($hookPoint) {
        if (!array_key_exists($hookPoint, $this->_pluginList)) return;
        $funcList = $this->_pluginList[$hookPoint];
        if (count($funcList) == 0) return;

        foreach ($funcList as $funcName) {
            if (function_exists($funcName)) {
                Widget_Probe::here('runHook hookPoint = ' . $hookPoint . ',funcName= ' . $funcName);

                call_user_func($funcName);
                Widget_Probe::here('After runHook hookPoint = ' . $hookPoint . ',funcName= ' . $funcName);

            } else {
                Tiri_Error::add("function is not exists $funcName", __FILE__, __LINE__);
            }
        }

    }

    public static function runShutdownHook() {
        Widget_Probe::here('App shutdown;');

        /** report hook in point shutdown */
        Tiri_Hook::getInstance()->runHook('shutdown');

    }
}

?>
