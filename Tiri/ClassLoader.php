<?php

class Tiri_ClassLoader {
    public function __construct() {
        spl_autoload_register(array($this, 'loader'));
    }

    // TODO::这里可以拦截文件不存在的问，通过扫所有include_path 的方法
    public static function loader($className) {
        $fileName = str_replace('_', '/', $className) . '.php';
        require $fileName;
    }
}