<?php

namespace Sloop\Core\Router;

use Sloop\Core\Request;


class CliResolver implements \Sloop\Inter\Resolver {

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
