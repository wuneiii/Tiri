<?php
class Tiri_App{

    private static $_instance;

    private static $_appRoot;

    private static $_autoLoadPath;

    private static $_appTempatePath;

    private static $_urlResolver;

    private static $_appResponse;

    private function __construct(){}

    /** app代表当前请求的Context 故全局单例*/
    static public function getInstance(){
        if(NULL == self::$_instance){
            self::$_instance = new Tiri_App();
        }
        return self::$_instance;
    }

    private function _initAddToIncludePath($arrPath){
        if(is_string($arrPath)){
            $arrPath = array($arrPath);
        }
        $path = get_include_path();
        foreach($arrPath as $value){
            $path .= PATH_SEPARATOR . $value;
        }
        set_include_path($path);      
    }
    /** 系统入口函数 ,加载配置，初始化组建*/
    public static function init(){

        error_reporting(E_ALL ^ E_NOTICE);
        ob_start();

        $app = self::getInstance();
        $app->_initErrorHandler();
        $app->_initAutoLoader();
        /** 加载系统核心库 */
        $app->_initAddToIncludePath( __TIRI_ROOT__ );
        
        // 加载配置文件
        $app->_initLoadConfig(); 
        date_default_timezone_set(Tiri_Config::get('app.timezone'));

        /** 设置App个性相关的全局变量*/
        self::$_appRoot = __APP_ROOT__;
        self::$_appTempatePath = __APP_ROOT__.Tiri_Config::get('app.templatePath');

        /** 初始化User 类 : 给指定一个数据Model*/
        /** User 加载cookie 必须在使用前init */
        $widgetUserConf = Tiri_Config::get('Widget_User');

        if( $widgetUserConf['model'] != ''){
            Widget_User::getInstance() -> init($widgetUserConf['model']);
        }
        /** 加载工具函数 */
        Func_Core::init();
        /** app资源管理类初始化 */
        Widget_Resource::init();
        /**   Geo 类要截取部分ajax请求，要修改系统路由表 ，必须在使用前初始化添加路由规则 */
        Func_Geo::init();

        Widget_Probe::startTimer();
        ob_clean();

        register_shutdown_function(array('Tiri_Hook' , 'runShutdownHook'));
    }

    public static function getAppTemplatePath(){
        return self::$_appTempatePath;
    }

    public static function getAppRootPath(){
        return self::$_appRoot;
    }

    public static function getUrlResolver(){
        if(self::$_urlResolver == null){

            if(!$resolver = Tiri_Config::get('app.urlResolver')){
                $resolver = 'Tiri_Router_Resolver'; 
            }
            self::$_urlResolver = new $resolver;
        }
        return self::$_urlResolver;
    }

    public static function getResponse(){
        if(self::$_appResponse == null){

            if(!$response = Tiri_Config::get('app.response')){
                $response = 'Tiri_Response'; 
            }
            self::$_appResponse = new $response;
        }
        return self::$_appResponse;
    }

    private function _initErrorHandler(){
        function exception_error_handler ($errno, $errstr, $errfile, $errline ) {
            throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
        }
        set_error_handler('exception_error_handler');     
    }

    private function _initAutoLoader(){
        function __autoload($className){
            $fileName = Tiri_App::getFileNameByClassName($className);
            try{
                include $fileName;
            } catch (ErrorException $e) {
                throw new Tiri_Exception(Tiri_Exception::CLASS_NOT_EXISTS, $className , $e);
            }
        }
    }
    public static function getFileNameByClassName($className) {
        return str_replace('_' , '/' ,$className).'.php';
    }      
    private function _initLoadConfig() {
        /** Load Config */
        Tiri_Config::loadConfigFile( __TIRI_ROOT__.'/config.inc.php' );
        Tiri_Config::loadConfigFile( __APP_ROOT__.'/config.inc.php' );

        if( file_exists( Tiri_Config::get('app.globalConfigFile') ) ){
            Tiri_Config::loadConfigFile( Tiri_Config::get('app.globalConfigFile'));
        }

        /** Load App Hook   */
        Tiri_Hook::loadHookFile( __APP_ROOT__ . '/hook.php' );
        /**  Run Hook   */
        Tiri_Hook::getInstance()->runHook('afterLoadConfig');

        /** 加载业务库   */
        if($appAutoLoadPath = Tiri_Config::get('app.autoLoadPath')){
            if(is_string($appAutoLoadPath))
                $appAutoLoadPath[] = $appAutoLoadPath;
                
            foreach($appAutoLoadPath as $path){
                if ($path == '') 
                    continue;
                $includePath = realpath( __APP_ROOT__.'/' . $path );
                if(file_exists( $includePath )){
                    $this->_initAddToIncludePath( $includePath );
                }
            }
        }
    }
}
