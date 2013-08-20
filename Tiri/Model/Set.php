<?php
    class Tiri_Model_Set{

        private $_model;
        private $_index = 0;
        private $_pool = array();

        /**
        * @param Model $model
        */
        public function add($model){
            if($this -> _model == null){
                $this -> _model = get_class($model);
            }
            /** 只要model的数据*/
            $this -> _pool[] = $model -> beAddedToSet();
        }
        public function isEnd(){
            return count($this-> _pool) == $this -> _index ;
        }

        public function total(){
            return count($this -> _pool);
        }
        /**
        * @return Model
        */
        public function getNext(){

            $model = $this -> _pool[$this -> _index];
            $this -> _index ++;
            return Tiri_Model::beReturnFromSet($this->_model , $model);
        }
        public function getIndex(){
            return $this -> _index;
        }
        /**
        * @return Model
        */
        public function getByIndex($index){
            if($index == count($this -> _pool)){
                return false;
            }
            return Tiri_Model::beReturnFromSet($this->_model , $this -> _pool[$index]);
        }
        /**
        * @return Model
        */
        public function first(){
            return $this -> getByIndex(0);
        }
        /**
        * @return Model
        */
        public function last(){
            return $this -> getByIndex(count($this -> _pool) -1);
        }
        public function resetIndex(){
            $this -> _index = 0;
        }
        public function setEmpty(){
            $this -> _pool = array();
        }

        public function isEmpty(){
            if(  count( $this ->_pool) == 0 )
                return true;
            return false;
        }

    }
?>
