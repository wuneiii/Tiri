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
                Log::info(__FILE__ ,__LINE__ ,$str .' 命中词典 '.$this -> _dict);
                Log::sendEvent(
                    "原始词语 {".$_rawStr."} 属于字典{".basename($this->_dict)."} 中的违禁词(findInDict)(完全匹配)", 
                    array() ,
                    Log::LOG_EVENT_HIT_FILTER_DICT
                );
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
                    Log::info(__FILE__ ,__LINE__ ,$str .' 匹配包含过滤词 '.$this -> _dict.' '.$word);
                    if(User::getInstance()->isLogined()){
                        $userInfo = User::getInstance()->getUserInfo();
                        Log::sendEvent(
                            "原始词语{".$_rawStr."} 中包含字典{".basename($this->_dict)."} 中的词语{". $word .'}(matchInDict)', 
                            array() ,
                            Log::LOG_EVENT_HIT_FILTER_DICT
                        );
                    }
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

                    Log::sendEvent(
                        "原始词语{".$_rawStr."} 命中词典{".basename($this->_dict)."}中的词条{".$word.'}被部分替换成星号(matchInDict)', 
                        array() ,
                        Log::LOG_EVENT_HIT_FILTER_DICT
                    );

                    Log::info(__FILE__ ,__LINE__ ,'敏感词过滤 '.$str .'中'.$word.'被替换成*');
                }
            }
            return $str;
        } 
    }