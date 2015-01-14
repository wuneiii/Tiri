<?php
    class Widget_Json{
        
        static public function beauty($json){
            $json = str_replace('],[' ,'],<br>[' , $json);
            $json = str_replace('}]' ,'}<br>]' , $json);
            $json = str_replace('[{' ,'[<br>{' , $json);
            $json = str_replace('[[' ,'[<br>[' , $json);
            $json = str_replace(']]' ,']<br>]<br>' , $json);
            $json = str_replace('},{' , '},<br>{' , $json);
            
            return $json;
        }
    }
    
    
?>
