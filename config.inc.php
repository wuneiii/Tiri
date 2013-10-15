<?php

Tiri_Config::set('app.globalConfigFile' , '');
Tiri_Config::set('app.autoLoadPath' , array('App'));
Tiri_Config::set('app.controllerParamName' , 'controller');
Tiri_Config::set('app.actionParamName' , 'action');
Tiri_Config::set('app.defaultController' , 'Login');
Tiri_Config::set('app.defaultAction' , 'index');
Tiri_Config::set('app.templatePath' , '/App/Template/');
Tiri_Config::set('app.timezone'             , 'Asia/Shanghai');

Tiri_Config::set('per_page'             , 10);

Tiri_Config::set('isUrlRewrite'         , false);
Tiri_Config::set('Widget_User'          , array('model' =>'Model_Manager'));

Tiri_Config::set('Widget_Resource' , 
    array(
        'css' => 'App/Resource/css' , 
        'js' => 'App/Resource/js' , 
        'images' => 'App/Resource/img'
    )
);

Tiri_Config::set('Widget_Db' , 
    array(
        'dbHost' => '127.0.0.1',
        'dbUser' => 'root',
        'dbPassword' => '',
        'dbName' => 'lmjd',
        'dbCharset' => 'utf8',
    )
);

