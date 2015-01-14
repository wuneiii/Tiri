<?php
    interface Widget_Db_Interface{
        
        public static function getInstance($conf);
        
        public function getErrorNo();
        
        public function getErrorMsg();
        
        public function report();
        
        
        
    }
?>
