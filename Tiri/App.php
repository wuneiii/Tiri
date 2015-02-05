<?php
/**
 * 全局单例的App类
 */
namespace Tiri;
use Tiri\Widget\Probe;

class App {

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
            self::$instance = new App();
        }
        return self::$instance;
    }

    /** 系统入口函数 ,加载配置，初始化组建*/
    public static function init() {

        error_reporting(E_ALL ^ E_NOTICE);
        ob_start();

        $app = App::getInstance();
        if ($app->isBoot) {
            return;
        }

        // 框架加入include_path
        $app->addToIncludePath(TIRI_ROOT);


        // 加载核心函数


        // 读取配置项目
        $app->loadConfig();

        // 把业务库加入include_path
        if ($appAutoLoadPath = Config::get(Constant::CONFIG_AUTO_LOAD_PATH)) {
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
        $timezone = Config::get(Constant::CONFIG_TIMEZONE);
        if ($timezone) {
            @date_default_timezone_set($timezone);
        }

        // APP ROOT 记录下来
        $app->_appRoot = APP_ROOT;
        $app->_tiriRoot = TIRI_ROOT;

        // 把单字函数加载进来
        require TIRI_ROOT. '/Func/Global.php';

        ob_clean();
        Probe::here('app init over');
    }

    public function getAppRootPath() {
        return $this->_appRoot;
    }

    public function getUrlResolver() {
        if ($this->_urlResolver == null) {
            $resolverClass = Config::get(Constant::CONFIG_CLASS_URL_RESOLVER);
            if (!$resolverClass) {
                $resolverClass = Constant::DEFAULT_CLASS_URL_RESOLVER;
            }

            $this->_urlResolver = new $resolverClass();
        }
        return $this->_urlResolver;
    }

    public function getResponse() {
        if ($this->_appResponse == null) {

            $responseClass = Config::get(Constant::CONFIG_CLASS_RESPONSE);
            if (!$responseClass) {
                $responseClass = Constant::DEFAULT_CLASS_RESPONSE;
            }
            $this->_appResponse = new $responseClass;
        }
        return $this->_appResponse;
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

    private function loadConfig() {
        // 加载 系统默认配置项，再加载app配置文件
        Config::loadConfigFile(TIRI_ROOT . '/config.inc.php');
        $appConfig = Config::loadConfigFile(APP_ROOT . '/config.inc.php');
        if (is_array($appConfig)) {
            foreach ($appConfig as $k => $v) {
                Config::set($k, $v);
            }
        }

        //加载跨项目配置文件
        $globalConfigFile = Config::get(Constant::CONFIG_GLOBAL_CONFIG);
        if (file_exists($globalConfigFile)) {
            $globalConfig = Config::loadConfigFile($globalConfigFile);
            if (is_array($globalConfig)) {
                foreach ($globalConfig as $k => $v) {
                    Config::set($k, $v);
                }
            }
        }

    }
}