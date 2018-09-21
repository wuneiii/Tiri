<?php

namespace Sloop\Lib;

use Sloop\Core\Config;
use Sloop\Core\Request;

class Resource {

    private static $urlPath = '';
    private static $isLoaded = false;
    private static $resPathPrefix = array('css' => '', 'js' => '', 'image' => '');

    public static function init() {
        if(!self::$isLoaded) {
            $conf = Config::get('app.resPathPrefix');
            if ($conf) {
                self::$resPathPrefix = $conf;
            }
            self::$urlPath = Request::getInstance()->getPath();
        }
    }

    public static function cssFilePath($fileName) {
        self::init();
        return self::$urlPath . self::$resPathPrefix['css'] . '/' . $fileName;
    }

    public static function jsFilePath($fileName) {
        self::init();
        return self::$urlPath . self::$resPathPrefix['js'] . '/' . $fileName;
    }

    public static function imageFilePath($fileName) {
        self::init();
        return self::$urlPath . self::$resPathPrefix['image'] . '/' . $fileName;
    }

}

