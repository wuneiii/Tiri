<?php

namespace Sloop;


if (!defined('APP_ROOT')) {
    die('Please define APP_ROOT!');
}
define ('TINY_ROOT', dirname(__FILE__));

require TINY_ROOT . '/Sloop/ClassLoader.php';

Tiri_ClassLoader::register();

Widget_Probe::startTimer();

Tiri_App::init();

Tiri_Router::dispose();


