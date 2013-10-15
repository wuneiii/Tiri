<?php
    class Func_Core{
        static public function init(){}
    }

    function T(){
        return Tiri_Template::getInstance();
    }
    /** 给入3个参数，生成url */
    function U($controler = '' , $action = '', $arrReq = array()){

        $_baseUrl       = Tiri_Request::getInstance() -> getPath();
        $_isUrlRewrite  = Tiri_Config::get('isUrlRewrite' , false);
        $controllerParamName    = Tiri_Config::get('app.controllerParamName');
        $actionParamName        = Tiri_Config::get('app.actionParamName');

        if($controler != ''){
            $arrReq[ $controllerParamName ] = $controler;
        }
        if($action != ''){
            $arrReq[ $actionParamName ] = $action;
        }

        $ret = $_baseUrl ;
        /** 没有url重写 */
        if( $_isUrlRewrite ){
           


        }else{
            $ret .= 'index.php?';
            if(count($arrReq) != 0){
                foreach($arrReq as $key => $value){
                    $ret  .= $key.'='.$value.'&';
                }
            }
            return substr($ret , 0 , (strlen($ret) - 1));

        }


    }

    function R($url){
        header('Location:'.$url);
        exit;
    }
    /**
    * @note 不推荐使用，应该用Widget_Db::getInstance
    * 获取一个数据库访问对象
    * 为了兼容老代码留着这个函数
    */
    function getdb(){
        return Widget_Db::getInstance();
    }

    function C($key) {
        return Tiri_Config::get($key, null);
    }

?>

