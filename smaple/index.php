<?php
    /** Tiri_Framework 默认入口文件*/
    
    /** 框架将按照url中指定的controller 和action 执行 app_lib 目录中指定的类函数*/
    /** 默认将执行 Controller_index.php 中的indexAction 方法*/
    /** config.inc.php 用户配置，可以改变默认controller和 action    */

    define( '__APP_ROOT__' , dirname(__FILE__).'/');

    define( '__APP_LIB__' , dirname(__FILE__).'/app/');

    require '../index.php';
?>
