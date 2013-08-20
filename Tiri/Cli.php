<?php
    class Tiri_Cli{

        static function runAction($controller , $action){

            $urlResolver = new Tiri_Router_CliResolver();
            $urlResolver -> setController( $controller );
            $urlResolver -> setAction( $action );

            Tiri_Config::set('app.resolver' , 'Tiri_Router_CliResolver');

            Tiri_Router::dispose();

        }

        /**
         * 取得内存使用
         * 
         * @param mixed $format
         */
        static function memoryUserd($format = true){

            $memUserd = memory_get_usage();
            if($format){
                return Func_Util::getRealSize($memUserd);
            }
            return $memUserd;
        }
        /**
         * 打开flush立即刷新
         * 
         */
        static function openImplicitFlush(){
            ob_end_flush(); 
            ob_implicit_flush(true);
        }
        /**
         * 设置最长时间
         */

        static function setTimeLimit($timeLimit){
            set_time_limit($timeLimit);
        }
        
        static function getPid(){
            return posix_getpid();
        }
    }
?>