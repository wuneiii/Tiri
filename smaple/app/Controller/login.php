<?php
    class Controller_login{
        
        public function indexAction(){
            if(Widget_User::getInstance() -> isLogined() === true){
                /**  Tiri 提供了若干个最常用的 单字母函数
                *    C() 获取配置项 ;
                *    R() header跳转 ;
                *    U('controller' , 'action', array('k'=>'v','k'=>'v')) 生成一个url
                *    T() 返回一个Tiri_Template 实例
                *    其他见Func_Core.php
                */
                R(U('main'));
                exit;
            }
            /** T() return a new Tiri_Template */
            T() -> render('framework/login.php');

        }
        
        public function logoutAction(){
            Widget_User::getInstance()->logout();
            R(U('login'));
        }

        public function authAction(){
            $wu = Widget_User::getInstance();
            $result = $wu -> login($_POST['username'] , $_POST['password']);
            //var_dump($result);
            
            if($result === true){
                R(U('main'));
            }else{
                echo U();
                R(U());
            }
        }
    }
?>
