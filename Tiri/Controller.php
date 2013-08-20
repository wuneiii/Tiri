<?php
    class Tiri_Controller{

        private static $_loaded;

        public static function factory($controller){
            if(!isset(self::$_loaded[$controller])){
                self::$_loaded[$controller] = new $controller();
            }
            return self::$_loaded [$controller];

        }
        
        
        public function __call($funcName , $argvList){
            $msg = 'call undefined action ['.$funcName.']';
            Tiri_Error::add($msg , __FILE__ , __LINE__);
            die($msg);
        }
    }
?>