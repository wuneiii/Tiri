<?php
    /** 系统核心部分配置项默认值    */
    Tiri_Config::set('controller_param_name'    , 'c');
    Tiri_Config::set('action_param_name'        , 'a');

    Tiri_Config::set('timezone'                 , 'Asia/Shanghai');
    Tiri_Config::set('per_page'                 , 10);


    $config['default_default_controller'] = 'index';
    $config['default_default_action'] = 'index';

    $config['default_per_page'] = 10;

    $config['default_db_charset'] = 'utf-8';

    $config['default_controller_path'] = 'controller/';
    $config['default_template_path'] = 'template/';

    $config['default_timezone'] = 'Asia/Shanghai';

    $config['default_isUrlRewrite'] = false;

?>
