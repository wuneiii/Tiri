<?php

namespace Sloop\Widget;

use Sloop\Core\Config;
use Sloop\Core\Request;

class Resource {
    private static $resPathPrefix = array('css' => '', 'js' => '', 'image' => '');

    public static function init() {
        $conf = Config::get('app.resPathPrefix');
        if (!$conf) {
            self::$resPathPrefix = $conf;
        }
    }

    public static function cssFilePath($fileName) {
        return Request::getInstance()->getPath() . self::$resPathPrefix['css'] . '/' . $fileName;
    }

    public static function jsFilePath($fileName) {
        return Request::getInstance()->getPath() . self::$resPathPrefix['js'] . '/' . $fileName;
    }

    public static function imageFilePath($fileName) {
        return Request::getInstance()->getPath() . self::$resPathPrefix['image'] . '/' . $fileName;
    }

}

