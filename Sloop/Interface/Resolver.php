<?php
    interface Tiri_Interface_Resolver{
        public function getController(Tiri_Request $req);
        public function getAction(Tiri_Request $req);
        public function getUrl($controller , $action , $argvs = array());   
    }
