<?php
    class Widget_Compress{
        public static function compress($type  , $data){
            return self::engine( $type.'Compress' , $data );
        }
        public static function unCompress($type ,$data){
            return self::engine( $type.'UnCompress' , $data);
        }

        public static function engine($func , $data){
            $compresser = new Widget_Compress();
            if(method_exists($compresser , $func)){
                return $compresser -> $func($data);
            }else{
                throw  Midou_Exception('compress type unsupported!');
            }
        }

        private function lzmaCompress($data){
            return $data;
        }
        private function lzmaUnComress($data){
            return $data;
        }
        
        
        private function gzipCompress($data){
            return $data;
        }
        private function gzipUnCompress($data){
            return $data;
        }
        
        private function deflateCompress($data){
            
        }
        private function deflateUnCompress($data){
            
        }

    }
?>
