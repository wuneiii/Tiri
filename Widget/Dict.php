<?php

    // 加载一个由行组成的过滤词字典，
    class Widget_Dict{
        public static $instance;
        private $_data;
        private $_dict;
        public function __construct(){

        } 

        public function getInstance(){
            if(self::$instance == null){
                self::$instance = new Widget_Dict();
            }
            return self::$instance;
        }

        public function loadDictFile($file){
            if(!file_exists($file)){
                return false;
            }
            //去除换行和空行
            $this -> _data = file($file , FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES); 
            $this -> _dict = $file;
        }

        public function findInDict($str){
            $_rawStr = $str;
            if(empty($this ->_data) || (count($this -> _data) == 0)){
                throw Exception('dict empty');
            }
            if(in_array($str , $this -> _data)){
                return true;
            }
            return false;
        }
        public function matchInDict($str){
            $_rawStr = $str;
            if(empty($this ->_data) || (count($this -> _data) == 0)){
                throw Exception('dict empty');
            }
            foreach($this -> _data as $word){
                //在str中找字典词
                if(false !== strpos($str , $word)){
                    return true;    
                }
            }
            return false;
        }

        public function replaceToAsterisk($str){

            $_rawStr = $str;
            if(empty($this ->_data) || (count($this -> _data) == 0)){
                throw Exception('dict empty');
            }
            foreach($this -> _data as $word){
                //在str中找字典词
                if(false !== strpos($str , $word)){
                    $str = str_replace( $word , str_repeat('*' , mb_strlen($word , 'UTF-8')) , $str);
                }
            }
            return $str;
        } 
    }
