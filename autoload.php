<?php

if( !defined('APP_ROOT')){
    die('NO APP_ROOT');
}

define ('SLOOP_ROOT', dirname(__FILE__));

require SLOOP_ROOT . '/Core/ClassLoader.php';

\Sloop\Core\ClassLoader::initSloop();
