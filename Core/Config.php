<?php

namespace Sloop\Core;

class Config {

    private static $instance;

    static public function getInstance() {
        if (null == self::$instance) {
            self::$instance = new Config();
        }
        return self::$instance;
    }

    private $config = array();

    private function __construct() {
        $defaultConfig = array(
            'sloop.ctlParam'    => 'controller',
            'sloop.actParam'    => 'action',
            'sloop.defaultCtl'  => 'Index',
            'sloop.defaultAct'  => 'index',
            'sloop.tplPath'     => '',
            'sloop.timezone'    => 'Asia/Shanghai',
            'sloop.urlResolver' => 'Sloop\Core\UrlResolver\DefaultUrlResolver',
            'sloop.response'    => 'Sloop\Core\Response',
            'app.tplPath'       => 'template',
            'app.tplExt'        => 'html',
            'app.resPathPrefix' => array(
                'css'   => '',
                'js'    => '',
                'image' => ''
            )
        );

        $this->setArray($defaultConfig);
    }


    public function loadConfigFile($file) {
        if (!file_exists($file)) {
            return false;
        }
        $config = @include_once($file);
        if (!$config) {
            Log::timeline('loadConfigFile fail [' . $file . ']');
            return false;
        }
        $this->setArray($config);
    }


    public function set($key, $value) {
        $this->config[$key] = $value;

    }

    public function setArray($array) {
        if (!is_array($array) || count($array) == 0) {
            return;
        }
        foreach ($array as $k => $v) {
            $this->set($k, $v);
        }
    }

    public function get($key, $default = null) {
        if (!isset($this->config[$key])) return $default;
        return $this->config[$key];
    }


    public function delete($key) {
        unset($this->config[$key]);
    }

    public function dump() {
        var_dump($this->config);
    }

}
