<?php
    class Widget_Resource{
        private static $_resConf = array('css'=>'','js'=>'','image'=>'');

        public static function init(){
            $conf = Tiri_Config::get('Widget_Resource');
            self::$_resConf = $conf;
        }

        public static function cssFile($fileName){
            return Tiri_Request::getInstance()->getPath().self::$_resConf['css'].'/'.$fileName;
        }
        public static function jsFile($fileName){
            return Tiri_Request::getInstance()->getPath().self::$_resConf['js'].'/'.$fileName;
        }
        public static function imageFile($fileName){
            return Tiri_Request::getInstance()->getPath().self::$_resConf['image'].'/'.$fileName;
        }

    }
?>
