<?php
use Tiri;
/**
 *
 * @param string $c
 * @param string $a
 * @param array $argv
 * @return mixed
 */
function U($c = '', $a = '', $argv = array()) {
    $resolver = Tiri\App::getInstance()->getUrlResolver();
    return $resolver->getUrl($c, $a, $argv);
}

function R($url) {
    header('Location:' . $url);
    exit;
}


function Conf($key) {
    return Tiri\Config::get($key, null);
}
