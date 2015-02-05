<?php
namespace Tiri;
class Controller {

    private static $_loaded;

    public static function factory($controller) {
        if (!isset(self::$_loaded[$controller])) {

            $newInstance = new $controller();
            self::$_loaded[$controller] = $newInstance;
        }
        return self::$_loaded [$controller];

    }
}