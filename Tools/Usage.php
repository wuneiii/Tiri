<?php
class Tools_Usage{

    private static $_instance;
    private $_page; // Tiri_Tempalte

    public static function getInstance() {
        if(self::$_instance == null){
            self::$_instance = new Tools_Usage();
        }
        return self::$_instance;
    }

    public function getDefaultVars (){
        return array(
            'title' => 'Tiri框架运行提示',
            'runTimeTitle' => '标题',
            'runTimeSuggestion' => '运行提示',
        );
    }
    public function getAppInternalParam(){
        $array = array(
            'app配置文件' => file_exists( __APP_ROOT__ . '/config.inc.php' ) ? '已加载:'.__APP_ROOT__ . '/config.inc.php' : '未找到',
            '默认配置文件' => file_exists( __TIRI_ROOT__ . '/config.inc.php' ) ? '已加载:'. __TIRI_ROOT__ . '/config.inc.php' : '未找到',
            'globalConfigFile' => Tiri_Config::get('app.globalConfigFile') == ''?'无' : Tiri_Config::get('app.globalConfigFile'),
            'appRoot' => Tiri_App::getAppRootPath(),
            'autoLoadPath' => implode('; ', Tiri_Config::get('app.autoLoadPath')),
            'include_path' => get_include_path(),
            'templatePath' => Tiri_App::getAppTemplatePath(),
            '默认控制器' => Tiri_Config::get('app.defaultController'),
            '默认动作' => Tiri_Config::get('app.defaultAction'),
            'timezone' => Tiri_Config::get('app.timezone'),
            'url解析器' =>  Tiri_Config::get('app.urlResolver') . '('.Tiri_App::getFileNameByClassName(Tiri_Config::get('app.urlResolver')).')',
            'app响应类' => Tiri_Config::get('app.response') . '('.Tiri_App::getFileNameByClassName(Tiri_Config::get('app.response')).')',
        ); 
        $ret = '';
        foreach($array as $key => $value){
            $ret .= '<li>'.$key .' ： '. $value .'</li>';
        }           
        return $ret;
    }
    public function showExceptionHelp($e , $extra = array()){ 
        if(!Tiri_Config::get('app.debug')){
            return;
        }  
        $this->_page = new Tiri_Template();
        $this->_page->assignArray(Tools_Usage::getInstance()->getDefaultVars());
        $this->_page->assign('appInternalParam', Tools_Usage::getInstance()->getAppInternalParam());
        $this->_page->assign('exceptionTrace', $e->getTraceAsString());

        switch($e->getCode()) {
            case Tiri_Exception::CLASS_NOT_EXISTS:
                $this->_handlerControllerNotExists($extra);
                break;
            default:
                $this->_handlerDefault($extra);
        }           

        $this->_page->renderBraceTemplate($this->outputTemplate());

    }
    private function _handlerDefault($vars) {

    }
    private function _handlerControllerNotExists($vars){
        $controllerName = $vars['controllerName'];
        $runTimeSuggestion = '<li>请在 include_path 目录下，创建文件' . Tiri_App::getFileNameByClassName($controllerName).'</li>';
        $runTimeSuggestion .= '<li>在该文件中定义 '.$controllerName.' 类</li>';

        $this->_page->assign('runTimeTitle', '错误：控制器 "'.$controllerName.'" 不存在');
        $this->_page->assign('runTimeSuggestion', $runTimeSuggestion);
    }
    // url解析器不存在
    public function urlResolverNotFound($e, $className) {
        $this->_page = new Tiri_Template();
        $this->_page->assignArray(Tools_Usage::getInstance()->getDefaultVars());
        $this->_page->assign('appInternalParam', Tools_Usage::getInstance()->getAppInternalParam());
        $this->_page->assign('exceptionTrace', $e->getTraceAsString());

        $runTimeSuggestion = '<li>请在 include_path 目录下，创建文件' . Tiri_App::getFileNameByClassName($className).'</li>';
        $runTimeSuggestion .= '<li>在该文件中定义 '.$className.' 类</li>';

        $this->_page->assign('runTimeTitle', '错误：Url解析器 "'.$className.'" 不存在');
        $this->_page->assign('runTimeSuggestion', $runTimeSuggestion);

        $this->_page->renderBraceTemplate($this->outputTemplate());

    }

    public function outputTemplate(){
        $template = <<<EOF
<html>
    <head>
        <title>{{title}}</title>
        <style type="text/css">
            .clear{ clear:both;}
            body,div,ul,li,h1,h2,p {margin:0px; padding:0px; font-size:14px;}
            body{ width:800px; margin:0px auto; background-color: #f7fbe9;}
            #t{  background: #333; color:#fff;  }
            #t ul li{ float:left;  list-style: none;  padding: 10px 40px; font-weight:bold;}
            #c{  background: #e8b36d; min-height: 100px; margin-top:10px; padding:10px;}
            #c p{border:1px solid yellow; padding:10px;margin:10px; color:red; font-weight:bold;}
            h2{ font-size:14px; padding:5px; display: block; }
            #c ul{ padding-left:40px;}
            #c ul li{list-style: decimal; line-height: 20px;}
        </style>
    </head>
    <body>
        <div id="t">
            <ul>
                <li>{{title}}</li>
            </ul>
            <div class="clear"></div>
        </div> 
        <div id="c">
            <h2>运行提示：</h2>
            <p>{{runTimeTitle}}</p>
            <ul>
                {{runTimeSuggestion}}
            </ul>
            <h2 style="margin-top:20px;">App运行全局参数：</h2>
            <ul>
                {{appInternalParam}}
            </ul>
            <h2 style="margin-top:20px;">Exception Trace：</h2>
            <ul>
                <pre>{{exceptionTrace}}</pre>
            </ul>
        </div>
    </body>
</html>
EOF;
        return $template;
    }
}