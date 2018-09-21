<?php

namespace Sloop\Core\UrlResolver;


use Sloop\Core\Config;
use Sloop\Core\Request;
use Sloop\Core\UrlResolverInterface;

class DefaultUrlResolver implements UrlResolverInterface {

    private $ctlParam;
    private $actParam;
    private $defaultC;
    private $defaultA;

    public function __construct() {
        $config = Config::getInstance();
        $this->ctlParam = $config->get('sloop.ctlParam');
        $this->actParam = $config->get('sloop.actParam');
        $this->defaultC = $config->get('sloop.defaultCtl');
        $this->defaultA = $config->get('sloop.defaultAct');
    }

    public function getController(Request $req) {
        $controller = $this->defaultC;
        if ($this->ctlParam) {
            $c = $req->getString($this->ctlParam);
            if ($c) {
                $controller = $c;
            }
        }
        return '\App\Controller\\' . ucfirst($controller);
    }

    public function getAction(Request $req) {
        $action = $this->defaultA;
        if ($this->actParam) {
            $a = $req->getString($this->actParam);
            if ($a) {
                $action = $a;
            }
        }
        return $action;
    }

    public function getUrl($controller, $action, $argv = array()) {
        if (!is_array($argv)) {
            $argv = array();
        }
        if (!$controller) {
            $controller = $this->defaultC;
        }
        if (!$action) {
            $action = $this->defaultA;
        }
        if ($this->ctlParam) {
            $argv[$this->ctlParam] = $controller;
        }
        if ($this->actParam) {
            $argv[$this->actParam] = $action;
        }


        $ret = 'index.php?';
        if ($argv) {
            foreach ($argv as $key => $value) {
                $ret .= $key . '=' . $value . '&';
            }
        }
        return substr($ret, 0, (strlen($ret) - 1));
    }
}