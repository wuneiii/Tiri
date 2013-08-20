<?php
    //**     业务配置文件样例
    
    $config['controller_param_name'] = 'action';
    $config['action_param_name'] = 'do';
    $config['per_page'] = 'do';

    $config['default_controller'] = 'index';
    $config['default_action'] = 'index';

    $config['per_page'] = 10;

    $config['db_charset'] = 'utf-8';

    $config['controller_path'] = 'controller/';
    
    $config['template_path'] = 'template/';
    $config['timezone'] = 'Asia/Shanghai';

    $config['isUrlRewrite'] = 'false';


    $config['Widget_Db'] = array(
    'dbHost' =>'',
    'dbUser' => '',
    'dbPassword' => '',
    'dbName' =>''
    );

    $config['Widget_User'] = array(
    /** widget_user 使用的用户model ，如果支持多用户登陆，需要变化这个参数*/
    /** 每次系统启动会将cookie加载到这个 model中，登陆验证，会用这个model 实例对应的db表验证    */
    'model' => 'UserSpace_Model_User',
    );
?>
