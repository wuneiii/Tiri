<?php
class Tiri_App{

    public static $instance;

    private $_appRoot;

    private $_autoLoadPath;

    private $_appTempatePath;

    private $_urlResolver;

    private $_appResponse;

    private function __construct(){}

    /** app代表当前请求的Context 故全局单例*/
    static public function getInstance(){
        if(null == self::$instance){
            self::$instance = new Tiri_App();
        }
        return self::$instance;
    }

    /** 系统入口函数 ,加载配置，初始化组建*/
    public static function init(){

        error_reporting(E_ALL ^ E_NOTICE);
        ob_start();

        $app = self::getInstance();
        $app->_initErrorHandler();
        $app->_initAutoLoader();
        /** 加载Tiri框架库 */
        $app->addToIncludePath (__TIRI_ROOT__);

        /**
        * 先读Tiri目录下的默认配置
        * 再读app目录下的，覆盖默认配置。
        * 如用户无配置，则保留了默认配置值
        * 
        * @var Tiri_App
        */
        $app->_initLoadConfig(); 
        date_default_timezone_set(Tiri_Config::get('tiri.timezone'));

        /** 
        *设置App个性相关的全局变量
        */
        $app->_appRoot = __APP_ROOT__;
        $app->_appTempatePath = realpath(__APP_ROOT__.Tiri_Config::get('tiri.templatePath')).'/';

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

        ob_clean();

        register_shutdown_function(array('Tiri_Hook' , 'runShutdownHook'));

    }

    public function getAppTemplatePath(){
        return $this->_appTempatePath;
    }
    public function setAppTemplatePath($path){
        $this->_appTempatePath = $path;
    }

    public function getAppRootPath(){
        return $this->_appRoot;
    }

    public function getUrlResolver(){
        if ($this->_urlResolver == null) {
            if(!$resolver = Tiri_Config::get('tiri.urlResolver')){
                $resolver = 'Tiri_Router_Resolver'; 
            }
            try{
                $this->_urlResolver = new $resolver;
            }catch(Tiri_Exception $e){
                Tools_Usage::getInstance()->urlResolverNotFound($e , $resolver);
                exit;
            }
        }
        return  $this->_urlResolver;
    }

    public function getResponse(){
        if($this->_appResponse == null){

            if(!$response = Tiri_Config::get('tiri.response')){
                $response = 'Tiri_Response'; 
            }
            $this->_appResponse = new $response;
        }
        return $this->_appResponse;
    }

    private function _initErrorHandler(){
        function exception_error_handler ($errno, $errstr, $errfile, $errline ) {
            throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
        }
        //set_error_handler('exception_error_handler');     
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

    private function _initLoadConfig() {
        /** Load Config */
        Tiri_Config::loadConfigFile( __TIRI_ROOT__.'/config.inc.php' );
        Tiri_Config::loadConfigFile( __APP_ROOT__.'/config.inc.php' );

        if( file_exists( Tiri_Config::get('tiri.globalConfigFile') ) ){
            Tiri_Config::loadConfigFile( Tiri_Config::get('tiri.globalConfigFile'));
        }

        /** Load App Hook   */
        Tiri_Hook::loadHookFile( __APP_ROOT__ . '/hook.php' );
        /**  Run Hook   */
        Tiri_Hook::getInstance()->runHook('afterLoadConfig');

        /** 加载业务库   */
        if($appAutoLoadPath = Tiri_Config::get('tiri.autoLoadPath')){
            if(is_string($appAutoLoadPath))
                $appAutoLoadPath[] = $appAutoLoadPath;

            foreach($appAutoLoadPath as $path){
                if ($path == '') 
                    continue;
                $includePath = realpath (__APP_ROOT__.'/' . $path);
                if (file_exists ($includePath) ) {
                    $this->addToIncludePath ($includePath);
                }
            }
        }
    }



    public function addToIncludePath($arrPath){
        if(is_string($arrPath)){
            $arrPath = array($arrPath);
        }
        $path = get_include_path();
        foreach($arrPath as $value){
            $path .= PATH_SEPARATOR . $value;
        }
        set_include_path($path);      
    }

    public static function getFileNameByClassName($className) {
        return str_replace('_' , '/' ,$className).'.php';
    }    
}
