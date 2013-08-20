<?php
        /**
        * Autogen @ 2012-02-12 13:49:40
        * mapping to 'geo_city' table of db 
        */
        class Func_Geo_Model_GeoCity extends Tiri_Model{
            public function __construct(){
                $this -> _table = 'geo_city';
                $this -> _primary_key = 'cid';
                $this -> _fields= array(
                'cid',
                'cityID',
                'city',
                'provinceID');
            }
        }
        ?>