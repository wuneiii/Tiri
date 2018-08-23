<?php

namespace Sloop\Core;

use Sloop\Core\Router\Resolver;
use Sloop\Widget\Probe;

class App {

    public static $instance;

    private $appRoot;
    private $sloopRoot;
    private $urlResolver;

    // 是否已经初始化完成
    public $isBooted;

    private function __construct() {
        $this->loadConfig();
        $this->initEnv();
    }


    static public function getInstance() {
        if (null == self::$instance) {
            self::$instance = new App();
        }
        return self::$instance;
    }


    // 非业务相关，环境构建相关工作
    public function initEnv() {

        if ($this->isBooted) {
            return;
        }

        error_reporting(E_ALL ^ E_NOTICE);

        // 时区
        $timezone = Config::get(Config::get('sloop.timezone'));
        if ($timezone) {
            @date_default_timezone_set($timezone);
        }

        //
        $this->appRoot = APP_ROOT;
        $this->sloopRoot = SLOOP_ROOT;
        $this->isBooted = true;


        Probe::here('app init over');
    }


    public function run() {
        $this->initUrlResolver();
        $this->disposeRequest();
    }


    private function loadConfig() {
        DefaultConfig::loadConfig();
    }

    private function initUrlResolver() {
        $this->urlResolver = new Resolver();
    }

    private function disposeRequest() {
        $router = new Router($this->urlResolver);
        $router->dispose();
    }


    public function registerAppNsPrefix($appNsPrefix){
        $sloopClsLoader = ClassLoader::getInstance();
        $sloopClsLoader->registerNamespace('\App\\', $appNsPrefix);
    }
}