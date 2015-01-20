<?php

/**
 *
 *
 * @date 2015-01-20 17:46:32
 */
class Func_Core {
    static public function init() {
    }
}


function U($c = '', $a = '', $argv = array()) {
    $baseUrl = Tiri_Request::getInstance()->getPath();
    $resolver = Tiri_App::getInstance()->getUrlResolver();
    return $resolver->getUrl($c, $a, $argv);
}

function R($url) {
    header('Location:' . $url);
    exit;
}


function Conf($key) {
    return Tiri_Config::get($key, null);
}
