<?php

namespace Sloop\Core\UrlResolver;

use Sloop\Core\Request;
use Sloop\Core\UrlResolverInterface;



class CliUrlResolver implements UrlResolverInterface {

    static $controller = '';
    static $action = '';

    public function setController($controller) {
        self::$controller = $controller;
    }

    public function setAction($action) {
        self::$action = $action;
    }

    public function getController(Request $req) {
        return 'Controller_' . self::$controller;
    }

    public function getAction(Request $req) {
        return self::$action . 'Action';
    }

    public function getUrl($controller, $action, $argvs = array()) {
    }
}
