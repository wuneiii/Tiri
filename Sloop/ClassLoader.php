<?php

namespace Sloop;

class ClassLoader {
    public static function register() {
        $callback = array(new Tiri_ClassLoader(), 'autoLoad');
        spl_autoload_register($callback);
    }

    public function autoLoad($className) {
        // 为兼容用户空间，类名中的下划线要要转换成文件路劲分隔符
        $fileName = str_replace('_', '/', $className);
        require $fileName;
    }
}