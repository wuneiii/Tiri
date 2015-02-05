<?php

class Func_Core {
    static public function load() {
    }
}

function T() {
    return Tiri_Template::getInstance();
}

/**
 * @param string $c
 * @param string $a
 * @param array $argv
 * @return mixed
 */
function U($c = '', $a = '', $argv = array()) {
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
