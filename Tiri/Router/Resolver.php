<?php
    class Tiri_Router_Resolver implements Tiri_Interface_Resolver{
        
        public function getController(Tiri_Request $req){

            if(Tiri_Config::get('isUrlRewrite')){

                $controller;
            }else{
                $paramName = Tiri_Config::get('controller_param_name');
                if(!$controller = $req -> getVal($paramName)){
                     $controller = Tiri_Config::get('default_controller');
                }
            }
            return 'Controller_'.$controller;


            return Tiri_Config::get('default_controller');

        }
        public function getAction(Tiri_Request $req){

            if(Tiri_Config::get('isUrlRewrite')){

                $action ;
            }

            $paramName = Tiri_Config::get('action_param_name');
            if(!$action = $req -> getVal($paramName)){
                $action = Tiri_Config::get('default_action');
            }
            return $action . 'Action';
        }
    }
?>