<?php


    define('__TIRI_ROOT__', dirname(__FILE__));

    set_include_path(get_include_path() 
    . PATH_SEPARATOR .  __TIRI_ROOT__);

    require_once __TIRI_ROOT__.'/Tiri/App.php';

    Tiri_App::cliInit();


?>
