<?php
/**
 * 框架入口文件
 */
if (!defined('APP_ROOT')) {
    die('Please define APP_ROOT!');
}
define ('TIRI_ROOT', dirname(__FILE__));

require TIRI_ROOT . '/Tiri/ClassLoader.php';

Tiri_ClassLoader::register();

Widget_Probe::startTimer();

Tiri_App::init();

Tiri_Router::dispose();
