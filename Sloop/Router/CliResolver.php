<?php
    class Tiri_Router_CliResolver implements Tiri_Interface_Resolver{

        static $controller = '';
        static $action = '';

        public function setController($controller){
            self::$controller = $controller;
        }
        public function setAction($action){
            self::$action = $action;
        }

        public function getController(Tiri_Request $req){
            return 'Controller_' . self::$controller;
        }
        public function getAction(Tiri_Request $req){
            return self::$action . 'Action';
        }
    }
?>