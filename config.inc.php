<?php
namespace Tiri;

Config::set('tiri.globalConfigFile', '');
Config::set('tiri.autoLoadPath', array(''));
Config::set('tiri.controllerParamName', 'controller');
Config::set('tiri.actionParamName', 'action');
Config::set('tiri.defaultController', 'Index');
Config::set('tiri.defaultAction', 'index');
Config::set('tiri.templatePath', '');
Config::set('tiri.timezone', 'Asia/Shanghai');
Config::set('tiri.urlResolver', 'Tiri\UrlResolver\Resolver');
Config::set('tiri.response', 'Tiri\Response');

Config::set('app.perPage', 10);

Config::set('Widget_User', array('model' =>''));

Config::set('Widget_Resource',
    array(
        'css' => '', 
        'js' => '', 
        'images' => ''
    )
);
Config::set('Widget_Db',
    array(
        'dbHost' => '127.0.0.1',
        'dbUser' => 'root',     
        'dbPort' => '3306',
        'dbPassword' => '',
        'dbName' => '',
        'dbCharset' => 'utf8',
    )
);

