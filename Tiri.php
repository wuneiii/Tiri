<?php
/**
 * 框架入口
 */

if (!defined('APP_ROOT')) {
    die('Please define App_Root!');
}

define ('TIRI_ROOT', dirname(__FILE__));


require TIRI_ROOT.'/Widget/Probe.php';

Widget_Probe::startTimer();

Widget_Probe::here('App start up now;');

require TIRI_ROOT.'/Tiri/App.php';

Tiri_App::init();

$appHook = Tiri_Hook::getInstance();
$appHook->runHook('afterAppInit');

Widget_Probe::here('After Tiri_App::init()');

$appHook->runHook('beforeDispose');

Widget_Probe::here('Before Tiri_Router::dispose();');

Tiri_Router::dispose();

Widget_Probe::here('After Tiri_Router::dispose();');

$appHook->runHook('afterDispose');
