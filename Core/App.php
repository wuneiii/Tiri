<?php

namespace Sloop\Core;

use Sloop\Lib\Probe;

class App {

    public static $instance;

    private $appRoot;
    private $sloopRoot;
    private $urlResolver;

    // 是否已经初始化完成
    private $isBooted;
    private $config;


    private function __construct() {
        $this->config = Config::getInstance();
        $this->loadAppConfig();
        $this->initEnv();
    }


    static public function getInstance() {
        if (null == self::$instance) {
            self::$instance = new App();
            Log::startTimer();
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
        $timezone = $this->config->get('sloop.timezone');
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


    public function loadAppConfig($userConfig = array()) {
        $appConfigFile = APP_ROOT . '/config/config.php';
        if (file_exists($appConfigFile)) {
            $this->config->loadConfigFile($appConfigFile);
        }
        if ($userConfig && is_array($userConfig)) {
            $this->config->setArray($userConfig);
        }
    }

    private function initUrlResolver() {
        $resolverName = $this->config->get('sloop.urlResolver');
        $this->urlResolver = new $resolverName;
    }

    private function disposeRequest() {
        $router = new Router($this->urlResolver);
        $router->dispose();
    }


    public function registerAppNsPrefix($appNsPrefix) {
        $sloopClsLoader = ClassLoader::getInstance();
        $sloopClsLoader->registerNamespace('\App\\', $appNsPrefix);
    }

    public function getUrlResolver() {
        return $this->urlResolver;
    }
}