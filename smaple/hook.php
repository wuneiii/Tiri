<?php
    /** 
    * @note 定义钩子函数，Tiri框架运行过程中，有若干环节放置有钩子，执行一些用户自定义方法
    * @example 比如 下边两个，结束时打印调试，配置加载完毕后，修改配置等等
    * @note 钩子列表尚未梳理给出，可查阅源码
    * 
    */

    //开发中，打印调试信息
    //发布后应注销此回调
    Tiri_Hook::getInstance() -> addHook('shutdown' , 'hookReport');

    function hookReport(){
        //打印整个过程中出现的错误
        Tiri_Error::dump();
        //全流程各个探针点的时间，用来分析各个环节的耗时
        Widget_Probe::report();
        //全部sql和单个运行时间
        Widget_Db::report();
    }
    
    //系统启动完成，开始运行业务前，记录用户行为日志
    //常用于后台管理界面，记录管理员操作
    Tiri_Hook::getInstance() -> addHook('afterAppInit' , 'hookLogAction');
    function hookLogAction(){
    
        $data['userid'] = Widget_User::getInstance()-> getCurUserId();
        $data['action'] = Tiri_Request::getInstance() -> getController();
        $data['do'] = Tiri_Request::getInstance()->getAction();
        $data['create_time'] = time();
        $data['param'] = serialize(array_merge($_POST , $_GET));
        //write to db or logfile
    }
?>