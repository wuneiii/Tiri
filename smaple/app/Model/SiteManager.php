<?php
        /**
        * Autogen @ 2013-10-02 20:27:34
        * mapping to 'site_manager' table of db 
        */
        class Model_SiteManager extends Tiri_Model{
            public function __construct(){
                $this -> _table = 'site_manager';
                $this -> _primary_key = 'id';
                $this -> _fields= array(
                'id',
                'username',
                'password',
                'enable',
                'regtime',
                'logintimes',
                'lastlogin',
                'lastip',
                'realname',
                'roles',
                'telephone',
                'email');
            }
        }
        ?>