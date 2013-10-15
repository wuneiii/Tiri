<?php
    class Tiri_Router_Resolver implements Tiri_Interface_Resolver{
        
        public function getController(Tiri_Request $req){

            if(Tiri_Config::get('isUrlRewrite')){

                $controller;
            }else{
                $paramName = Tiri_Config::get('app.controllerParamName');
                if(!$controller = $req -> getVal($paramName)){
                     $controller = Tiri_Config::get('app.defaultController');
                }
            }
            return 'Controller_'.$controller;
        }
        public function getAction(Tiri_Request $req){

            if(Tiri_Config::get('isUrlRewrite')){

                $action ;
            }

            $paramName = Tiri_Config::get('app.actionParamName');
            if(!$action = $req -> getVal($paramName)){
                $action = Tiri_Config::get('app.defaultAction');
            }
            return $action . 'Action';
        }
    }
?>