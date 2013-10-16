<?php
    class Tiri_Router{

        const ROUTER_CONTINUE = 1;

        const ROUTER_STOP = 2;

        private static $_fixedRouter = array();
        
        private static $_resolver;

        public static function addFixedRouter($controller , $action , $hookInFunc){

            self::$_fixedRouter[] = array($controller , $action , $hookInFunc);

        }

        public static function dispose(){

            $req = Tiri_Request::getInstance();

            
            self::$_resolver = Tiri_App::getUrlResolver();
            $appController = self::$_resolver -> getController($req);
            $appAction  = self::$_resolver -> getAction($req);


            /** 1step */
            /** run fixed router */

            if(count(self::$_fixedRouter) != 0 ){
                foreach(self::$_fixedRouter as $router){
                    if(count($router) != 3)
                        continue;
                    if($router[0] == $appController && $router[1] == $appAction){
                        Widget_Probe::here('Before run fixed router;['.$router[0] .' : '.$router[1].' -> '.$router[2].']');

                        $ret = call_user_func($router[2]);
                        Widget_Probe::here('After run fixed router;');

                        if($ret != self::ROUTER_CONTINUE){
                            return;
                        }
                    }
                }
            }

            Widget_Probe::here('Before run default router;['.$appController .' : '.$appAction.']');

            $ret = Tiri_Controller::factory( $appController ) -> $appAction( Tiri_Request::getInstance());
            
            Widget_Probe::here('After run default router;');
            
            //ob_get_clean();

            $response = Tiri_App::getResponse();
            $response -> send($ret);

        }
    }