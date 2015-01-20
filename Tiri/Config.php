<?php

/**
 * 配置项目容器
 *
 * @version 0.3.8
 * @date 2015-01-20 18:26:08
 *
 */
class Tiri_Config {


    private static $_config;

    /**
     * 加载配置文件
     * @param $file
     * @return bool
     */
    static public function loadConfigFile($file) {

        if (strpos($file, 'config.inc.php') === false) {
            return false;
        }
        if (!file_exists($file) && !is_readable($file)) {
            return false;
        }
        require_once $file;
    }


    static public function set($key, $value) {
        self::$_config[$key] = $value;
    }

    static public function setArray($array) {
        if (!is_array($array) || count($array) == 0) {
            return;
        }
        foreach ($array as $k => $v) {
            self::set($k, $v);
        }
    }

    static public function get($key, $default = '') {
        if (isset(self::$_config[$key])) {
            return self::$_config[$key];
        }
        if ($default) {
            return $default;
        }
        return false;
    }


    static public function delete($key) {
        if (isset(self::$_config[$key])) {
            unset(self::$_config[$key]);
        }
    }

    static public function dump() {
        var_dump(self::$_config);
        var_dump(self::$_global);
    }
}