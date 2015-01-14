<?php
class Func_Core{
    static public function init(){}
}

function T(){
    return Tiri_Template::getInstance();
}
/** 给入3个参数，生成url */
function U($c = '', $a = '', $argv = array()){
    $baseUrl  = Tiri_Request::getInstance()->getPath();
    $resolver = Tiri_App::getInstance()->getUrlResolver();
    return $resolver->getUrl($c, $a, $argv);
}

function R($url){
    header('Location:'.$url);
    exit;
}



function Conf($key) {
    return Tiri_Config::get($key, null);
}
