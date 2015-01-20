<?php


    class Widget_SimpleSlice extends Tiri_Widget{

        private $_file;

        private $_data;

        public function __construct($name){

            $sliceName = str_replace(array(',','.',';',':',"\n") , '_' , trim($name));
            $dirName = './._Slice/';
            $this -> _file = $dirName . $sliceName;

            if(!file_exists($dirName)){
                mkdir($dirName , 0775 , true);
            }
            if(!file_exists($dirName . $sliceName)){
                touch($this -> _file);
            }
            $content = file($this -> _file);
            foreach($content as $line){
                list($k , $v) = explode("=" , chop($line));
                if($k == '')continue;
                $this -> _data[$k] = $v;
            }

        }
        public function set($key ,$value){
            $value = str_replace("\n" , '\n', $value);
            $this -> _data[$key] = $value;
            $this -> writeToDisk();
        }
        public function keyExists($key){
            if(!is_array($this -> _data) || count($this -> _data) == 0 ){
                return false;
            }
            return in_array($key , array_keys($this -> _data));        
        }
        public function get($key){

            $value = $this -> _data[$key]; 
            $value = str_replace('\n' , "\n", $value);
            return $value;

        }
        public function del($key){
            unset($this -> _data[$key]);
            $this -> writeToDisk();
        }
        private function writeToDisk(){
            foreach($this -> _data as $k =>$v){
                $content .= $k.'='.$v."\n";
            }
            file_put_contents( $this -> _file , $content);
        }

        public function getFirstKey(){
            $ak = array_keys($this -> _data);
            return $ak[0];
        }
    }