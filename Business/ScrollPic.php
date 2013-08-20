<?php
    class Business_ScrollPic{

        private $_modelName;

        public function __construct(){
            $conf = C('Business_ScrollPic');
            $model = 'Business_Model_ScrollPic';
            $this -> _modelName = Tiri_Model_Util::mapTableToModel($conf['table'] , $model);
        }

        public function getAll(){
            $sql = "SELECT *FROM __TABLE__ WHERE id = spid";
            $allSp = $this -> _modelName -> exec_sql($sql);
            return $allSp;
        }

        public function newSp($spName){
            if($spName == '')
                return;
            $this -> _modelName -> setEmpty();
            $this -> _modelName -> desc = $spName;
            $id = $this -> _modelName -> save_to_db();

            $this -> _modelName -> setEmpty();
            $this -> _modelName -> setID($id);
            $this -> _modelName -> spid = $id;
            $this -> _modelName -> save_to_db();
        }
        
        public function getSp($id){
            $this -> _modelName -> setEmpty();
            $this -> _modelName -> spid = $id;
            $allPic = $this -> _modelName -> getAllMatch();
            return $allPic;
        }
        public function newPic($array){
        
            $this -> _modelName -> setEmpty();
            $this -> _modelName -> fillFromArray($array);
            $this -> _modelName -> save_to_db();
        }
        
        public function getPic($id){
            $this -> _modelName -> setEmpty();
            $this -> _modelName -> loadById($id);
            $ret = clone $this -> _modelName;
            return $ret;
        }
        public function updatePicInfo($array){
            $this -> _modelName -> setEmpty();
            $this -> _modelName -> fillFromArray($array);
            $this -> _modelName -> setId($array['picid']);
            $this -> _modelName -> save_to_db();
        }
        
        public function getJsData($id){
        
            $ret = '';
            $this -> _modelName -> setEmpty();
            $this -> _modelName -> spid = $id;
            $allPic = $this -> _modelName -> getAllMatch();
            if($allPic -> isEmpty()){
                return '[];';
            }
            $ret = "[\n";
            while(!$allPic -> isEnd()){
                $pic = $allPic -> getNext();
                if($pic -> spid == $pic -> id)
                    continue;
                    $ret .= "{img:'".$pic -> pic_url."',url:'".$pic -> url."',title:'".$pic -> title."',desc:'".$pic -> desc."'},\n";
            }
            $ret .="]";
            return $ret;
        }
    }
?>
