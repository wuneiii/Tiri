<?php
    class Business_Model_ScrollPic extends Tiri_Model{
        public function __construct(){
            $this -> _table = 'cms_scrollpic';
            $this -> _primary_key = 'id';
            $this -> _fields= array(
            'id',
            'pic_url',
            'url',
            'title',
            'desc',
            'spid',
            'is_del');
        }
    }  
?>
