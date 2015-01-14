<?php
    class Widget_Db extends Tiri_Widget{

        /** 存放 一个具体的数据库驱动实例 */
        private static $_instance;

        private static $_driver = 'Mysql';

        private function __construct(){}
        
        static public function getInstance(){
            if( self::$_instance ==  null){
                $self = new Widget_Db();
                self::$_instance = $self -> init();
            }
            return self::$_instance;
        }

        /** 返回一个可供使用的 Widget_Db 对象  */
        private function init(){
            $conf = Tiri_Config::get('Widget_Db');

            if(self::$_driver == 'Mysql'){

                return Widget_Db_Mysql::getInstance($conf);

            }else{

                die('Error: 引擎 ['.$_driver . '] 尚未实现');

            }

        }
        
        static function fetch_assoc($query) {
            $query = mysql_fetch_row($query , MYSQL_ASSOC);
            return $query;
        } 
        static function fetch_object($query) {
            $query = mysql_fetch_object($query);
            return $query;
        }
        
        static public function report(){
            Widget_Db::getInstance()->report();
        }
        static public function ping() {
            self::$_instance->ping();
        }
    }
?>