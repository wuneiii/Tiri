<?php
namespace Tiri\Func;
    class Fs{
        static public function rm($file ){
            return unlink($file);
        }
        
        static public function fileSize($filePath){
            $stat = stat($filePath);
            return $stat['size'];
        }
    }
