<?php
/**
* @date 2013-10-15 16:16:58
* @desc 默认配置，如果app配置文件中会覆盖这里的配置
* 
*/

// app. 下配置项目为Tiri内部使用配置项，会影响Tiri行为
Tiri_Config::set('tiri.globalConfigFile', '');
Tiri_Config::set('tiri.autoLoadPath', array(''));
Tiri_Config::set('tiri.controllerParamName', 'controller');
Tiri_Config::set('tiri.actionParamName', 'action');
Tiri_Config::set('tiri.defaultController', 'Index');
Tiri_Config::set('tiri.defaultAction', 'index');
Tiri_Config::set('tiri.templatePath', '');
Tiri_Config::set('tiri.timezone', 'Asia/Shanghai');
Tiri_Config::set('tiri.urlResolver', 'Tiri_Router_Resolver');
Tiri_Config::set('tiri.response', 'Tiri_Response');

Tiri_Config::set('app.perPage', 10);

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

