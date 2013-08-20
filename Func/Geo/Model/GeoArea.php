<?php
        /**
        * Autogen @ 2012-02-12 13:49:40
        * mapping to 'geo_area' table of db 
        */
        class Func_Geo_Model_GeoArea extends Tiri_Model{
            public function __construct(){
                $this -> _table = 'geo_area';
                $this -> _primary_key = 'aid';
                $this -> _fields= array(
                'aid',
                'areaID',
                'area',
                'cityID');
            }
        }
        ?>