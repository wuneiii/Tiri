<?php
namespace Tiri\Widget;
    class Mongodb{

        private $_config;
        private $_mongoClient;

        public function __construct(){
            $this -> _config = Tiri_Config::get("Mongodb");
            if(!$this -> _config){
                return false;
            }
            $this -> _mongoClient = new MongoClient("mongodb://".$this -> _config);        
        }
        
        public function getCollection($db , $collection){
            $db = $this -> _mongoClient -> selectDB($db);
            return new MongoCollection($db ,$collection);
        }

        public function set($db , $table , $id , $doc){
            $doc[ 'data' ] = new  MongoBinData( $doc['data'] , MongoBinData::BYTE_ARRAY ) ;
            $collection = $this -> _instance -> $db -> $table;
            return $collection -> insert( array_merge(array('_id' => $id) , $doc) );
        }

        public function setDoc($db ,$table , $doc){
            $collection = $this -> _instance -> $db -> $table;
            return $collection -> insert($doc);
        }


        public function get($db , $table , $id){
            $collection = $this -> _instance -> $db -> $table;
            $ret = $collection -> findOne(array('_id' => new MongoId($id)));
            $ret['data'] = $ret['data']->bin;
            return $ret;
        }
        public function query($db , $table , $query){
            $ret = array();
            $collection = $this -> _mongoClient -> $db -> $table;
            $cursor = $collection -> find( $query );
            if(!$cursor -> count()){
                return $ret;
            }

            foreach($cursor as $doc){
                $ret [] = $doc;    
            }

            return $ret;
        }


        public function removeDoc($db , $table ,$doc){
            $collection = $this -> _instance -> $db -> $table;
            return $collection -> remove($doc);
        }

        public function count($db , $table , $id){
            $collection = $this -> _instance -> $db -> $table;
            return $collection -> count(array('_id'=> new MongoId($id)));
        }
        public function delete($db ,$table , $id){
            $collection = $this -> _instance -> $db -> $table;
            return $collection -> remove(array('_id' => new MongoId($id)));
        }

    }
