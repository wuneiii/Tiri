<?php

if (!defined('APP_ROOT')) {
    die('Please define APP_ROOT!');
}
define ('TIRI_ROOT', dirname(__FILE__));

require TIRI_ROOT . '/Tiri/ClassLoader.php';

Tiri_ClassLoader::register();

Tiri_App::init();