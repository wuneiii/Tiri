<?php
    class Tiri_Error{
        private static $_error;


        public static function add($msg , $file ,$line){
            self::$_error[] = array('msg'=>$msg , 'file'=>$file , 'line'=>$line);
        }

      

        static public function dump(){
            echo '<div><p>[Debug]Tiri-Framework Error Dump:</p><ol>';
            if(is_array (self::$_error))
                foreach(self::$_error as $error){
                    echo "<li>[File\t:\t".$error['file']."]\t[Line\t:\t".$error['line']."]\t[Msg\t:\t".$error['msg']."]</li>";
            }
            echo '</ol><div>';
        }
    }
?>
