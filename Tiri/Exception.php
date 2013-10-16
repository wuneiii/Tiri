<?php
class Tiri_Exception extends Exception{
    
    const CLASS_NOT_EXISTS = 1;

    public function __construct($code, $message, $preException){
        switch ($code) {
            case self::CLASS_NOT_EXISTS:
                $message = '类{' . $message . '}不存在';
        }  
        parent::__construct($message, $code, $preException);
    }
}
