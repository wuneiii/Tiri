<?php
    class Widget_Cookie{

        static public function get($key,$default = NULL){
            $_sign = $_COOKIE['__sign__'.$key];
            $_value = str_replace('\\', '', $_COOKIE[$key]);

            if($_value != '' && $_sign == self::getSign($_value)){
                return $_value;
            }
        }

        static public function set($key, $value, $expire = 86400, $domain = ''){
            $expire += time();
            setcookie($key, $value, $expire, $domain);
            setcookie('__sign__' . $key, self::getSign($value), $expire, $domain);
        }

        static public function delete($key){
            setcookie($key, null, -1);
            setcookie('__sign__' . $key, null, -1);
        }

        static public function getSign($string){
            $min = 9;
            $signLength = strlen($string) > $min ? $min : strlen($string);
            return md5(substr($string , 0,$signLength). substr($string , (0 - $signLength)));
        }
    }

?>