<?php

class Tiri_Controller {

    private static $_loaded;

    public static function factory($controller) {
        if (!isset(self::$_loaded[$controller])) {

            try {
                $newInstance = new $controller();
            } catch (Tiri_Exception $e) {
                if ($e->getCode() == Tiri_Exception::CLASS_NOT_EXISTS) {
                    Tools_Usage::getInstance()->showExceptionHelp($e, array('controllerName' => $controller));
                    exit();
                }
            }
            self::$_loaded[$controller] = $newInstance;
        }
        return self::$_loaded [$controller];

    }

    public function R($url) {
        header('Location:' . $url);
        exit;
    }

}