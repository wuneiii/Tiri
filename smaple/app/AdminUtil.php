<?php
    class AdminUtil{

        static function printNavBar(){
            $_cont = Tiri_Request::getInstance() -> getController();
            $_act = Tiri_Request::getInstance() ->  getAction();

            $mainNav = $_cont;
            $subNav = $_act;

            return '当前位置 > ' .$mainNav .' > '.$subNav;
        }

        static function getColIdByCatId($_catid){
            $config = C('articleCat');
            foreach($config as $colid => $data){
                if(!is_array($data))
                    continue;
                foreach($data as $catId =>$name){
                    if($catId == $_catid)
                        return $colid;
                }
            }
        }

        static function getFristCatId($colid){
            $config = C('articleCat');
            foreach($config[$colid] as $id =>$name)
                return $id;
        }

    }
?>
