<?php
    class Controller_main {
        public function __construct(){
            if( Widget_User::getInstance()-> isLogined() === false){
                R(U('login'));
                exit;
            }
        }
        public function indexAction(){
            $t = new Tiri_Template();
            $t -> render('framework/main.php');

        }
    }
?>
