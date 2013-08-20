<?php
    class Func_Fs{
        static public function rm($file ){
            return unlink($file);
        }
    }
?>
