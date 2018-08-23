<?php

namespace Sloop\Core;

class Config {

    private static $config;
    private static $instance;

    private function __construct() {

    }

    static public function getInstance() {
        if (null == self::$instance) {
            self::$instance = new Config();
        }
        return self::$instance;
    }

    static public function loadConfigFile($file) {
        if (!file_exists($file)) return false;
        require_once $file;

    }


    static public function set($key, $value) {
        self::$config[$key] = $value;

    }

    static public function setArray($array) {
        if (!is_array($array) || count($array) == 0) {
            return;
        }
        foreach ($array as $k => $v) {
            self::set($k, $v);
        }
    }

    static public function get($key, $default = null) {
        if (!isset(self::$config[$key])) return $default;
        return self::$config[$key];
    }


    static public function delete($key) {
        unset(self::$config[$key]);
    }

    static public function dump() {
        var_dump(self::$config);
    }

}
