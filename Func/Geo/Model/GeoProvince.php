<?php
        /**
        * Autogen @ 2012-02-12 13:49:40
        * mapping to 'geo_province' table of db 
        */
        class Func_Geo_Model_GeoProvince extends Tiri_Model{
            public function __construct(){
                $this -> _table = 'geo_province';
                $this -> _primary_key = 'pid';
                $this -> _fields= array(
                'pid',
                'provinceID',
                'pname');
            }
        }
        ?>