<?php
if (!defined('__APP_ROOT__')) {
    die('No App_Root!');
}

define ('__TIRI_ROOT__', dirname(__FILE__));

require_once __TIRI_ROOT__.'/Widget/Probe.php';
/**
* 开始计时表，随时可以调用计时来获取运行时间
*/
Widget_Probe::startTimer();
//第一根性能探针
Widget_Probe::here('App start up now;');

require_once __TIRI_ROOT__.'/Tiri/App.php';
Tiri_App::init();

$appHook = Tiri_Hook::getInstance();
$appHook->runHook('afterAppInit');

Widget_Probe::here('After Tiri_App::init()');

$appHook->runHook('beforeDispose');

Widget_Probe::here('Before Tiri_Router::dispose();');

Tiri_Router::dispose();

Widget_Probe::here('After Tiri_Router::dispose();');

$appHook->runHook('afterDispose');
