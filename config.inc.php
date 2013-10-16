<?php
/**
* @date 2013-10-15 16:16:58
* @desc 默认配置，如果app配置文件中会覆盖这里的配置
* 
*/

// app. 下配置项目为Tiri内部使用配置项，会影响Tiri行为
Tiri_Config::set('app.globalConfigFile', '');
Tiri_Config::set('app.autoLoadPath', array(''));
Tiri_Config::set('app.controllerParamName', 'controller');
Tiri_Config::set('app.actionParamName', 'action');
Tiri_Config::set('app.defaultController', 'Index');
Tiri_Config::set('app.defaultAction', 'index');
Tiri_Config::set('app.templatePath', '');
Tiri_Config::set('app.timezone', 'Asia/Shanghai');
Tiri_Config::set('app.urlResolver', 'Tiri_Router_Resolver');
Tiri_Config::set('app.response', 'Tiri_Response');

Tiri_Config::set('per_page', 10);

Tiri_Config::set('isUrlRewrite', false);
Tiri_Config::set('Widget_User', array('model' =>''));

Tiri_Config::set('Widget_Resource', 
    array(
        'css' => '', 
        'js' => '', 
        'images' => ''
    )
);
Tiri_Config::set('Widget_Db', 
    array(
        'dbHost' => '127.0.0.1',
        'dbUser' => 'root',     
        'dbPort' => '3306',
        'dbPassword' => '',
        'dbName' => '',
        'dbCharset' => 'utf8',
    )
);

