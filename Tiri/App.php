<?php

class Tiri_App {

    public static $instance;

    private $_appRoot;
    private $_tiriRoot;
    private $_urlResolver;
    private $_appResponse;

    // 是否已经初始化完成
    public $isBoot;

    private function __construct() {
    }

    /** app代表当前请求的Context 故全局单例*/
    static public function getInstance() {
        if (null == self::$instance) {
            self::$instance = new Tiri_App();
        }
        return self::$instance;
    }

    /** 系统入口函数 ,加载配置，初始化组建*/
    public static function init() {

        error_reporting(E_ALL ^ E_NOTICE);
        ob_start();

        $app = Tiri_App::getInstance();
        if ($app->isBoot) {
            return;
        }

        // 框架加入include_path
        $app->addToIncludePath(TIRI_ROOT);

        // 启动class loader
        new Tiri_ClassLoader();

        // 读取配置项目
        $app->loadConfig();

        // 把业务库加入include_path
        if ($appAutoLoadPath = Tiri_Config::get(Tiri_Const::CONFIG_AUTO_LOAD_PATH)) {
            if (is_string($appAutoLoadPath))
                $appAutoLoadPath[] = $appAutoLoadPath;

            foreach ($appAutoLoadPath as $path) {
                if ($path == '')
                    continue;
                $includePath = realpath(APP_ROOT . '/' . $path);
                if (file_exists($includePath)) {
                    $app->addToIncludePath($includePath);
                }
            }
        }

        // 时区
        $timezone = Tiri_Config::get(Tiri_Const::CONFIG_TIMEZONE);
        if ($timezone) {
            @date_default_timezone_set($timezone);
        }

        // APP ROOT 记录下来
        $app->_appRoot = APP_ROOT;
        $app->_tiriRoot = TIRI_ROOT;

        ob_clean();

    }


    public function getAppRootPath() {
        return $this->_appRoot;
    }

    public function getUrlResolver() {
        if ($this->_urlResolver == null) {
            $resolverClass = Tiri_Config::get(Tiri_Const::CONFIG_CLASS_URL_RESOLVER);
            if (!$resolverClass) {
                $resolverClass = Tiri_Const::DEFAULT_CLASS_URL_RESOLVER;
            }
            $this->_urlResolver = new $resolverClass;
        }
        return $this->_urlResolver;
    }

    public function getResponse() {
        if ($this->_appResponse == null) {

            $responseClass = Tiri_Config::get(Tiri_Const::CONFIG_CLASS_RESPONSE);
            if (!$responseClass) {
                $responseClass = Tiri_Const::DEFAULT_CLASS_RESPONSE;
            }
            $this->_appResponse = new $responseClass;
        }
        return $this->_appResponse;
    }

    private function loadConfig() {
        // 加载 系统默认配置项，再加载app配置文件
        Tiri_Config::loadConfigFile(TIRI_ROOT . '/config.inc.php');
        Tiri_Config::loadConfigFile(APP_ROOT . '/config.inc.php');

        //加载跨项目配置文件
        $globalConfigFile = Tiri_Config::get(Tiri_Const::CONFIG_GLOBAL_CONFIG);
        if (file_exists($globalConfigFile)) {
            Tiri_Config::loadConfigFile($globalConfigFile);
        }

    }


    public function addToIncludePath($arrPath) {
        if (is_string($arrPath)) {
            $arrPath = array($arrPath);
        }
        $path = get_include_path();
        foreach ($arrPath as $value) {
            $path .= PATH_SEPARATOR . $value;
        }
        set_include_path($path);
    }

}