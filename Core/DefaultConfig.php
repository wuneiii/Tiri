<?php


namespace Sloop\Core;


class DefaultConfig {

    public static function loadConfig() {


        Config::set('sloop.ctlParam', 'controller');
        Config::set('sloop.actParam', 'action');
        Config::set('sloop.defaultCtl', 'Index');
        Config::set('sloop.defaultAct', 'index');
        Config::set('sloop.tplPath', '');
        Config::set('sloop.timezone', 'Asia/Shanghai');
        Config::set('sloop.urlResolver', 'Sloop\Core\Router\Resolver');
        Config::set('sloop.response', 'Sloop\Core\Response');

        Config::set('app.tplPath', 'template');
        Config::set('app.tplExt' , 'html');

        Config::set('app.resPathPrefix', array(
            'css'   => '',
            'js'    => '',
            'image' => ''
        ));

    }
}