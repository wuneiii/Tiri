<?php
namespace Tiri;

use Tiri\Widget\Probe;

/**
 * 框架入口文件
 */

if (!defined('APP_ROOT')) {
    die('Please define APP_ROOT!');
}
define ('TIRI_ROOT', dirname(__FILE__));

require TIRI_ROOT . '/Tiri/ClassLoader.php';
ClassLoader::register();

Probe::startTimer();

App::init();

Router::dispose();
