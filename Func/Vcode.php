<?php
    class Func_Vcode{

        public static function getCodeImg(){
            $code = $_GET['code'];
            $width = $_GET['w'];
            $height = $_GET['h'];
            self::genVcodeImg($code , $width , $height);
            return Tiri_Router::ROUTER_STOP;
        }

        static public function genVcodeImg($code , $w = '', $h = ''){
            ///如果之前有输出，全部清理掉::wq
            
            ob_get_clean();

            header("Content-type: image/PNG"); 
            //准备好随机数发生器种子  
            //准备图片的相关参数   
            
            if($h == '')$h = 20;
            if($w == '')$w = 62;
            $im = imagecreate( $w , $h ); 
            $black = ImageColorAllocate($im, 0,0,0);  //RGB黑色标识符 
            $white = ImageColorAllocate($im, 255,255,255); //RGB白色标识符 
            $gray = ImageColorAllocate($im, 200,200,200); //RGB灰色标识符 
            //开始作图     
            if($code == '')
                $code = 'noStr';
            imagefill($im,0,0,$gray); 
            imagestring($im, 5, 10, 3, $code, $black); 
            //加入干扰象素    
            for($i=0;$i<200;$i++){ 
                $randcolor = ImageColorallocate($im,rand(0,255),rand(0,255),rand(0,255)); 
                imagesetpixel($im, rand()%70 , rand()%30 , $randcolor); 
            } 
            //输出验证图片 
            ImagePNG($im); 
            //销毁图像标识符 
            ImageDestroy($im); 
        }
    }
?>
