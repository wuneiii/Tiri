<?php
namespace Tiri;

if (!defined('APP_ROOT')) {
    die('Please define APP_ROOT!');
}
define ('TIRI_ROOT', dirname(__FILE__));

require TIRI_ROOT . '/Tiri/ClassLoader.php';

ClassLoader::register();

App::init();