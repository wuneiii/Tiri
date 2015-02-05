<?php
namespace Tiri;
/*
[QUERY_STRING] => a=b=c
[REQUEST_URI] => /admin/index.php/a/b/c?a=b=c
[SCRIPT_NAME] => /admin/index.php
[PATH_INFO] => /a/b/c
[PATH_TRANSLATED] => E:\some_code\2012-06-30-with-zhujia\a\b\c
[PHP_SELF] => /admin/index.php/a/b/c
*/
class Request{
    public static $instance;
    private $_controller;
    private $_action;
    private $_method;
    private $_remoteIp;
    private $_refer;
    private $_host;
    private $_port;
    private $_path;

    private function __construct(){}

    static public function getInstance(){
        if(null == self::$instance){
            self::$instance = new Request();
            self::$instance->analysis();
        }
        return self::$instance;
    }
    public function analysis(){
        $app = App::getInstance();
        $urlResolver = $app->getUrlResolver();

        $this->_controller  = $urlResolver->getController($this);
        $this->_action      = $urlResolver->getAction($this);

        $this->_host =  $_SERVER['HTTP_HOST'];
        $this->_port = $_SERVER['SERVER_PORT'];
        $this->_remoteIp    = $this->getIp();
        $this->_method      = strtolower($_SERVER['REQUEST_METHOD']);

        $this->_path = dirname(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        //todo::检查输入
        if(isset($_SERVER['HTTP_REFERER'])){      
            $this->_refer = $_SERVER['HTTP_REFERER'];
        }
    }
    
    public function getParam($key){
        if(isset($_GET[$key])){
            return $_GET[$key];
        }else if(isset($_POST[$key])){
            return $_POST[$key];
        }
        return null;
    }
    public function getInt($key){
        return intval($this->getParam($key));
    }
    public function getString($key){
        return strval($this->getParam($key));
    }
    public function getArray($key){
        $val = $this->getParam($key);
        if(!is_array($val)){
            return array();
        }
        return $val;
    }
    public function getFile($key){
        if(isset($_FILES[$key])){
            return $_FILES[$key];
        }
        return null;
    }
    public function isPost(){
        return strtolower($this->_method) == 'post';
    }
    public function getUrlWithQuery($key , $value){

        $ret  = $this->_path;
        if(!is_array($_GET)){
            return $ret.'?'.$key.'='.$value;
        }
        $ret .='?';
        $_GET[$key] = $value;
        foreach($_GET as $key => $value){
            $ret .= $key.'='.$value."&";
        }
        return substr($ret , 0 , strlen($ret) - 1);
    }
    public function getUrl(){
        $ret  = $this->_path.'index.php';
        if(!is_array($_GET)){
            return $ret;
        }
        $ret .='?';
        foreach($_GET as $key => $value){
            $ret .= $key.'='.$value."&";
        }
        return substr($ret , 0 , strlen($ret) - 1);
    }
    public function getController(){
        return $this->_controller;
    }
    public function getAction(){
        return $this->_action;
    }
    public function getPath(){
        return $this->_path;
    }
    public function getRefer(){
        return $this->_refer;
    }
    public function getHost(){
        return 'http://'. $this->_host;
    }
    public function getMethod(){
        return $this->_method;    
    }
    public function getRequestUrl(){
        return $_SERVER['REQUEST_URI'];
    }
    public function getIp(){
        // TODO ::检查输入， 这些自动都可以被手动设置
        if (isset($_SERVER)) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $realip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $realip = $_SERVER['HTTP_CLIENT_IP'];
            } else {
                $realip = $_SERVER['REMOTE_ADDR'];
            }
        } else {
            if (getenv("HTTP_X_FORWARDED_FOR")) {
                $realip = getenv( "HTTP_X_FORWARDED_FOR");
            } elseif (getenv("HTTP_CLIENT_IP")) {
                $realip = getenv("HTTP_CLIENT_IP");
            } else {
                $realip = getenv("REMOTE_ADDR");
            }
        }
        return $realip;
    }

}