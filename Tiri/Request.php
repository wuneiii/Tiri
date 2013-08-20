<?php
    /*
    [QUERY_STRING] => a=b=c
    [REQUEST_URI] => /admin/index.php/a/b/c?a=b=c
    [SCRIPT_NAME] => /admin/index.php
    [PATH_INFO] => /a/b/c
    [PATH_TRANSLATED] => E:\some_code\2012-06-30-with-zhujia\a\b\c
    [PHP_SELF] => /admin/index.php/a/b/c
    */
    class Tiri_Request{

        private $_controller;

        private $_action;

        private $_method;

        private $_remoteIp;

        private $_refer;

        private $_host;

        private $_port;

        private $_path;

        private static $_instance;

        private function __construct(){
            $this -> analysis();
        }

        /**
         * @deprecated
         * 
         * @param mixed $key
         */
        public function getVal($key){
            return $this -> getParam($key);
        }


        public function getParam($key){
            if(isset($_GET[$key])){
                return $_GET[$key];
            }else if(isset($_POST[$key])){
                return $_POST[$key];
            }
            return null;
        }
        
        public function getFile($key){
            if(isset($_FILES[$key])){
                return $_FILES[$key];
            }
            return null;
        }

        static public function getInstance(){
            if(NULL == self::$_instance){
                self::$_instance = new Tiri_Request();
            }
            return self::$_instance;
        }

        public function analysis(){

            $this -> _host =  $_SERVER['HTTP_HOST'];
            $this -> _port = $_SERVER['SERVER_PORT'];
            $this -> _remoteIp = self::getIp();
            $this -> _method = $_SERVER['REQUEST_METHOD'];


            $this -> _controller = Tiri_App::getUrlResolver() -> getController($this);
            $this -> _action = Tiri_App::getUrlResolver() -> getAction($this);

            $sn = explode('/',$_SERVER['SCRIPT_NAME']);
            $sn[count($sn)-1] = '';     
            $this -> _path = implode('/',$sn);       

            $this -> _refer = $_SERVER['HTTP_REFERER'];
        }

        public function param($key , $default = null){
            switch(true){
                case isset($_GET[$key]):
                    return $_GET[$key];
                case isset($_POST[$key]):
                    return $_POST[$key];
                default:
                    return $default;
            }
        }

        public function getUrlWithQuery($key , $value){
            $ret  = $this -> _path.'index.php';
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
            $ret  = $this -> _path.'index.php';
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
            return $this -> _controller;
        }
        public function getAction(){
            return $this -> _action;
        }
        public function getPath(){
            return $this -> _path;
        }

        public function getRefer(){
            return $this -> _refer;
        }

        public function getMethod(){
            return $this -> _method;    
        }
        
        public function getRequestUrl(){
            return $_SERVER['REQUEST_URI'];
        }

        public static function getIp(){
            if (isset($_SERVER)) {
                if (isset($_SERVER[HTTP_X_FORWARDED_FOR])) {
                    $realip = $_SERVER[HTTP_X_FORWARDED_FOR];
                } elseif (isset($_SERVER[HTTP_CLIENT_IP])) {
                    $realip = $_SERVER[HTTP_CLIENT_IP];
                } else {
                    $realip = $_SERVER[REMOTE_ADDR];
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
?>