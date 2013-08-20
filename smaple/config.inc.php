<?php
    $config['controller_param_name'] = 'action'; //url中controller的参数名为action 
    $config['action_param_name'] = 'do'; //url中action的参数名字为do
    $config['default_controller'] = 'login';//默认寻找login controller
    $config['template_path'] = 'app/Template/';

    /** db相关配置 */
    $config['Widget_Db']['dbHost'] = 'localhost';
    $config['Widget_Db']['dbName'] = 'test';
    $config['Widget_Db']['dbUser'] = 'root';
    $config['Widget_Db']['dbPassword'] = '';
    $config['Widget_Db']['charset'] = 'utf8';

    /** 配置urlrewrite  ,默认为false. U() 函数 生成url时会受此配置影响  */
    $config['isUrlRewrite'] = false;

    /** 静态资源配置  */
    $config['Widget_Resource'] = array(
    'css'=>'css',
    'js'=>'js',
    'image'=>'images',
    );

    /** 默认User类用的model，若业务不使用用户，不需要这个配置 */
    $config['Widget_User'] = array(
    'model' => 'Model_SiteManager'
    );

    /**  业务自定义的配置项，在全局任何位置 使用 C('site_name') 函数可以获取值    */
    $config['site_name'] = 'Tiri框架样例网站';





?>
