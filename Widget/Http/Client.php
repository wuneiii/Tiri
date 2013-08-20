<?php
    class Widget_Http_Client{

        private $_header = array();
        private $_lastUrl;
        private $_timeout;

        public function __construct(){
            $this -> addHeader('User-Agent' , 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.97 Safari/537.11');
            $this->setTimeout(5);
        }
        public function addHeader($key , $value){
            $this -> _header[] = $key . ':' . $value;
        }
        public function setTimeout($t){
            $this -> _timeout = $t;
        }

        public function sendRequest($url){
            Widget_Probe::here('Before Widget_Http_Client::sendRequest('.$url.')');
            $this -> _lastUrl = $url;

            $ch = curl_init(); 
            curl_setopt($ch, CURLOPT_URL, $url); 
            curl_setopt($ch, CURLOPT_HEADER, FALSE); 
            curl_setopt($ch, CURLOPT_HTTPHEADER , $this -> _header);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
            curl_setopt($ch, CURLOPT_TIMEOUT, 5); 
            $httpContent = curl_exec($ch); 
            if(!$httpContent){
                Log::monitor(
                    __FILE__,
                    __LINE__,
                    'httpClient请求出错,url是{'.$url.'},错误为{'.var_export( curl_error($ch), true).'}'
                );
            }
            curl_close($ch); 
            Widget_Probe::here('After Widget_Http_Client::sendRequest('.$url.')');
            return $httpContent;
        }


        public function post($url , $postData){

            Widget_Probe::here('Before Widget_Http_Client::sendRequest('.$url.')');
            $this -> _lastUrl = $url;
            Log::fatal(__FILE__,__LINE__ , var_export( $postData ,true));

            $ch = curl_init(); 
            curl_setopt($ch, CURLOPT_URL, $url); 
            curl_setopt($ch, CURLOPT_HEADER, FALSE); 
            curl_setopt($ch, CURLOPT_HTTPHEADER , $this -> _header);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
            curl_setopt($ch, CURLOPT_TIMEOUT, $this->_timeout);
            curl_setopt($ch, CURLOPT_POST, TRUE); 
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            $httpContent = curl_exec($ch); 
            if(!$httpContent){
                Log::monitor(
                    __FILE__,
                    __LINE__,
                    'httpClient请求出错,url是{'.$url.'},错误为{'.var_export( curl_error($ch), true).'}'
                );
            }
            curl_close($ch); 
            Widget_Probe::here('After Widget_Http_Client::sendRequest('.$url.')');

            return $httpContent;

        }

        public function getLastUrl(){
            return $this -> _lastUrl;
        }
    }
