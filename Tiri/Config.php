<?php
    class Tiri_Config{

        private static $_config;
        private static $_global;

        private function __construct(){

        }
        static public function getInstance(){
            if(null == self::$_instance){
                self::$_instance = new Tiri_Config();
            }
            return self::$_instance;
        }

        static public function loadConfigFile($file){
            if(!file_exists($file))
                return false;
            require_once $file;

        }
        /**
         * @deprecated
         * 
         * @param mixed $config
         */
        static public function loadConfig($config = array()){
            if(count($config) > 0){
                foreach($config as $key => $value){
                    self::$_config[$key] = $value;
                } 
            }
        }

        static public function setGlobal($key , $value){
            self::$_global[ $key ] = $value; 
        }
        static public function getGlobal($key){
            return self::$_global[ $key ];
        }

        static public function set($key , $value){
            self::$_config[ $key ] = $value;

        }
        static public function get($key , $default = null){
            if(!isset( self::$_config[ $key ]) )
                return null;
            return self::$_config[ $key ];
        }
        static public function dump(){
            var_dump(self::$_config);
        }

        static public function delete($key){
            unset(self::$_config[ $key ]);
        }

    }
?>