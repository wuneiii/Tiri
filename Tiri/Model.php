<?php
    /**
     * @desc  Object/Relation Mapping
     * @author  tirisfal.sing@gmail.com
     * @version v0.3
     * @site  http://www.tirisoft.com
     * @copyright reserved
     * @depend  $db = function getdb()
     * @depend  mysql style sql
     *
     **/
    /**
     * @update v0.4 
     * @date 2012年3月18日
     * @增加了orm内用于描述数据显示的结构，第一版如下
     * $this->fields = array(
     'id'=> array('type'=> HTML_TYPE_PK,'hidden'=>1),
     * @然后发现不能这样使用，因为id=>value,value 存储了实际数据，这个值是要映射如db的持久层的
     * @不能用来存储显示相关的结构
     * 
     * @增加了attrs字段，这个字段orm核心和数据库交互的部分不适用，仅为外部AUTO-FROM使用
     * 
     */
    /**
     * @update v0.5
     * @date 2012年4月16日
     * @为了在有条件的时候做分页，在get-all-match和get_total_nums 函数的sql生成时，增加了where的生成。
     * @算修bug，不改变函数直白的设计目标
     */
    /**
     * @date 2012年7月8日22:48:30
     * @desc ： get_insert_id
     */

    /**
     * 升级入Tiri 0.3时代，Orm正式更名为Model类，为客户Model的基类
     * 
     * 1.改名
     * 2.所有属性变成私有属性
     * 3.暂时不修改SOM类，后续有时间在梳理SOM.刚好som有不少想法。期间som类不可用了。
     * 
     * @date 2012年12月1日15:11:08
     */
    /**
     * tiri0.3开发第二天，Model 是否单例的问题思考：
     * 1.如果单例model仅能代表 一行数据或者一个空模型，无法用来表示查询的结果集
     * 2.如果不单例，Model的初始化方法有点混乱，空的也能，有一个id也能用，查询结果是充实的也能用。
     * 3.结论： Model 是单例的，仅能表示一个空的模型，或者一行数据
     *          查询结果集用，Model_Set 表示，是一个有序的model集合体，对外提供next，total，遍历等方法，用来操作内部的Model对象。
     *           为了避免ModelSet浪费空间，Model_Set仅保存有序的id
     *   再者，大量的数据存储为Model对象，浪费了大量的空间，需要一个轻量级的仅保存数据的对象集合
     */
    /**
     * @date 2012年12月2日17:47:03
     * @desc 0.3版本基本定稿
     */
    class Tiri_Model{

        /**
         * @desc  table name in db
         * */
        protected  $_table;

        /**
         * @desc all fields name in this table
         * */
        protected  $_fields = array();

        /**
         * @desc value of fields
         */
        protected  $_values = array();

        /**
         * @desc keys($attrs) map to keys($fields);
         * @var mixed
         * @since 2012年3月18日
         */
        protected  $_attrs = array();

        /** $primary_key */
        protected  $_primary_key = 'id';

        /** 单例模式中的已初始化对象 ,Model 私有，子Model不能持有*/
        private static $_loaded;

        /** 单例，不许实例化*/
        private function __construct(){}

        /** 工厂 */
        static public function factory($modelName){
            if(self::$_loaded[$modelName] == NULL){
                self::$_loaded[$modelName] = new $modelName;
            }
            return self::$_loaded[$modelName];
        }

        /** 不要直接给 ->id 赋值，有可能主键不叫id*/
        public function setId($id){
            $this -> _values[$this->_primary_key] = $id;
        }
        public function getId(){
            return $this -> _values[$this->_primary_key];

        }
        public function setTable($tableName){
            $this -> _table = $tableName;
        }
        /** 清空一个Mdole */
        public function setEmpty(){
            $this -> _values = array();
        }


        /** 添加到set中 */
        public function beAddedToSet(){
            return $this -> _values;
        }
        /** 从set return出来的时候 */
        public static function beReturnFromSet($modelName , $_values){
            $modelInstance = Tiri_Model::factory($modelName);
            $modelInstance -> fillFromArray($_values);
            return $modelInstance;
        }


        public function __get($key){
            if(in_array($key,$this->_fields)){
                return $this->_values[$key];
            }
            return NULL;
        }

        public function __set($key, $value){
            if(in_array($key,$this->_fields)){
                $this->_values[$key] = $value;
                return true;
            }
            return false;
        }
        /**
         * @desc for debug
         * */
        public function dump(){
            var_dump($this->_values);
        }
        /**
         * @Deprecated this;
         * @Recommend use like style "$obj->key = value"(__set)
         * @desc fill the $obj->key = value
         * @input k=>v
         * @return true or false
         * @desc 放弃，推荐使用__set,以kv的形式填充obj,
         * */
        public function fillFromKv($key, $value){
            if(in_array($key,$this->_fields)){
                $this->_values[$key] = $value;
                return true;
            }
            return false;
        }
        /**
         * @desc fill obj from a array
         * @input array
         * @return void
         * */
        public function fillFromArray($array = array()){
            if(!is_array($array))
                return;
            foreach($array as $k => $v){
                $this->fillFromKv($k , $v);
            }
        }

        /**
         * @return array(obj) or null
         * @input 至少有一个查询添加，否则返回null
         * @desc 通常用于查询，基本能支持各种select,fetchAll 函数就退出历史舞台
         */
        public function getAllMatch($order = '' , $limit = '' , $where = ''){
            /** 空*/
            if(count ($this -> _values) == 0){}
            //return null;
            /** 生成sql */
            $sql = "SELECT * FROM " . $this -> _table . " WHERE 1=1 ";
            foreach($this->_values as $k => $v){
                if( $v !== NULL ){
                    $sql .= " AND `$k` ='$v' ";
                }
            }
            if($where != ''){
                $sql .= " AND $where";
            }
            /** 处理条件，这部分有风险 */
            if($order == ''){
                $order = ' ORDER BY ' . $this -> _primary_key. '  DESC ';
            }
            $sql .= $order. ' ';
            $sql .= $limit=='' ? '' : $limit;

            /** 取数据 */
            $db = Widget_Db::getInstance();
            $qry = $db ->query($sql);
            $modelSet = new Tiri_Model_Set();

            if($qry == false || ($db->num_rows($qry) == 0)){
                return $modelSet;
            }
            /** 填充Set里*/
            while($rs = $db->fetch_array($qry)){
                $modelName = get_class($this);
                $tmp = new $modelName;
                $tmp -> fillFromArray($rs);
                $modelSet -> add( $tmp );
            }
            return $modelSet;
        }
        /**
         * 按sort顺序去最后一条记录
         * 
         * @param mixed $order  如果为空，则按 主键排序 
         * @param mixed $sort  决定升降序
         * @param mixed $where  where 条件，可任意组合
         * @return Tiri_Model_Set 如果为空，返回false
         */
        public function getLastMatch($order ='' , $sort = '', $where = ''){

            /** 处理where条件   */
            $sql = "SELECT * FROM " . $this -> _table ;
            if(count ($this -> _values) != 0){
                $sql .= " WHERE 1=1 ";
                foreach($this->_values as $k => $v){
                    if( $v !== null ){
                        $sql .= " AND `$k` ='$v' ";
                    }
                }
                $sql .= $where;
            }else if($where){
                $sql .= " WHERE 1=1 AND ".$where;
            }

            /** order 字段    */
            if($order == '' || !$this -> keyExists($order)){
                $order = $this -> _primary_key;
            }
            /**  desc or asc    */
            if($sort == '')
                $sort = 'DESC';
            $sql .= ' ORDER BY ' . $order .' '. $sort .' LIMIT 1';

            /** 取数据 */
            $db = Widget_Db::getInstance();

            if(!($qry = $db ->query($sql)) || !($rs = $db -> fetch_array($qry))){
                return false;    
            }
            $modelName = get_class($this);
            $dataModel = new $modelName;
            $dataModel -> fillFromArray($rs);
            return $dataModel;
        }


        /***
        * @return array(ojb)
        * @input
        * @desc 主要用来显示列表页面
        * @desc fetchAll方法改名为pager ，专为pager服务，查询统一到上边的getAllMatch
        */
        public function pager($conf = array('start'=> 0 ,'limit'=> 0 ,'orderField' =>'','orderType'=>'DESC' ,'where' => '')){

            if( $conf['orderField'] == ''){
                $conf['orderField'] = $this -> _primary_key;
            }
            if( $conf['limit'] == '0'){
                $conf['limit'] = Tiri_Config::get('per_page');
            }
            if( $conf['orderType'] == '' ){
                $conf['orderType'] = 'desc';
            }


            return $this -> getAllMatch(
                " order by " . $conf['orderField'] . " ".$conf['orderType']."  ",
                " limit " . $conf['start'] .','.$conf['limit'],
                $conf['where'] );

        }

        /**
         * @desc 对给定ID从数据库里拿数据, ”“”填充obj本身“”“
         * @desc 依赖主键
         * @input id
         * @return true or false
         * */
        public function loadById($id = ''){

            $pk = $this->_primary_key;
            /** empty(0) == true */
            $id = $id==='' ? $this->_values[$pk] : $id;
            if(!is_numeric($id) || intval($id) != $id){
                return false;
            }

            $sql = 'SELECT * FROM `'.$this->_table.'` WHERE '.$pk.' = '.$id;
            return $this -> __load($sql);
        }
        public function loadByUniqueKey($key,$value){

            if(!in_array($key,$this->_fields)){
                return false;
            }
            $sql = 'SELECT * FROM `'.$this->_table.'` WHERE '.$key.' = \''.$value.'\'';
            return $this -> __load($sql);
        }
        public function loadByMatch(){
            $sql = "SELECT * FROM " . $this -> _table . " WHERE 1=1 ";
            foreach($this->_values as $k => $v){
                if( $v != NULL ){
                    $sql .= " AND `$k` ='$v'";
                }
            }
            return $this -> __load($sql);
        }
        private function __load($sql){

            $db = Widget_Db::getInstance();
            /** load 系方法仅填充对象，如果是结果集就不对*/
            if(!$qry = $db -> query($sql)){
                return false;
            }
            $cnt = $db -> num_rows($qry);
            if($cnt == 0){
                return false;
            }
            if($cnt > 1){
                Log::debug(__FILE__,__LINE__,'Tiri_Model 中的 _load 方法找到了多条记录,$this -> {'.var_export($this , true).'}');
                return false;
            }
            if(!$rs = $db -> fetch_array($qry)){
                return false;
            }
            $this -> fillFromArray($rs);
            return true;

        }
        /**
         * 删除唯一主键记录，否则返回false
         * 
         * @param mixed $id
         */
        public function deleteById($id = ''){

            $pk = $this->_primary_key;
            $id = empty($id) ? $this->_values[$pk] : $id;

            if(!is_numeric($id) || intval($id) != $id){
                return false;
            }
            $sql = 'DELETE  FROM `' . $this -> _table .'` WHERE '.$pk.' = '.$id;
            $db = Widget_Db::getInstance();
            return $db->query($sql);
        }
        /**
         * 除主键外其他唯一字段作为删除条件，删除唯一记录
         * 
         * @param mixed $key
         * @param mixed $value
         */
        public function deleteByUniqueKey($key,$value){
            if(!in_array($key,$this->_fields)){
                return false;
            }
            if($value == ''){
                return false;
            }
            $sql = 'DELETE FROM `' . $this -> _table . '` WHERE ' . $key.' = '.$value;
            $db = Widget_Db::getInstance();
            if(!$qry = $db->query($sql))
                return false;
            return true;
        }
        /**
         * 没有主键值，根据fields中各字段条件匹配删除，为空的条件不会被用上。
         * 
         * @note 可能会误删。
         */
        public function delete_from_db_by_match(){

        }

        public function lockId($id){
            $sql = "INSERT INTO `". $this -> _table ."` (" .$this-> _primary_key. ") VALUES ('$id')";
            $db = Widget_Db::getInstance();
            $db -> query($sql);
            $last_id = $db -> insert_id();
            if($id === $last_id){
                return true;
            }
            return false;
        }

        /**
         * @desc 持久化obj对象到db中
         * @return $qry
         * */
        public function save_to_db(){

            $pk = $this->_primary_key;
            /** 如果主键存在 */
            if($this->_values[$pk] != NULL && is_numeric($this->_values[$pk])){
                $sql = 'UPDATE `'.$this -> _table.'` SET ';
                foreach($this->_values as $k =>$v){

                    /** 主键不写在这里*/
                    if( $k  ==  $pk )    {
                        continue;
                    }
                    $sql .= '`'.$k.'`=\''.$v.'\',';
                }
                $sql = substr($sql,0,-1)." WHERE ".$pk." = '".$this -> _values[$pk]."'";
            }else{
                /** 主键没有 */;
                foreach($this -> _values as $k => $v){
                    if($this -> _values[$k] == null ) 
                        continue;


                    $fields .= "`$k`,";
                    $values .= "'".$v."',";
                }
                $sql = 'INSERT INTO `'.$this -> _table 
                .'` ('.substr($fields,0,-1).') VALUES ('.substr($values,0,-1).')';

            }
            $db = Widget_Db::getInstance();
            if(!$db->query($sql)){
                return false;
            }
            if($this -> _values[$pk] != null){
                return $this -> _values[$pk];
            }
            return $db -> insert_id();
        }

        /**
         * @date:2012年11月20日8:58:54
         * @为了便于有地方要直接操作sql
         * @这样调用破坏了orm的封装，不推荐
         */
        public function exec_sql($sql){
            $db = Widget_Db::getInstance();
            $sql = str_replace('__TABLE__',$this->_table,$sql);
            $qry = $db -> query($sql);
            if($qry == false || ($db->num_rows($qry) == 0)){
                return new Tiri_Model_Set();
            }
            /** 填充Set里*/
            $modelSet = new Tiri_Model_Set();
            while($rs = $db->fetch_array($qry)){
                $modelName = get_class($this);
                $tmp = new $modelName;
                $tmp -> fillFromArray($rs);
                $modelSet -> add( $tmp );
            }
            return $modelSet;
        }

        public function get_query_exec_sql($sql){
            $db = Widget_Db::getInstance();
            $sql = str_replace('__TABLE__',$this->_table,$sql);
            $qry = $db -> query($sql);
            return $qry;
        }

        /**
         * 取得这个数据表里数据量总和
         * 给分页等使用
         * 由于fetch_all等需要取数据等，效率问题，专门出来这个函数
         */

        public function getTotalNums($where = ''){
            /**
             * @date 2012年4月16日0:26:21
             * @同上为了给有条件的分页做总数统计，增加where字段的这一段代码
             */
            if(count($this -> _values) != 0){
                foreach($this -> _values as $k => $v){
                    if($v !== ''){
                        $requirement .= " `$k` ='$v' AND ";
                    }
                }
                if($where == ''){
                    $requirement = substr($requirement , 0 , strlen($requirement) - 5);
                }else{
                    $requirement .= $where;
                }

            }else{
                $requirement = $where;
            }

            $sql  = "SELECT COUNT(*) as count FROM ".$this -> _table;
            if($requirement != '')
                $sql .= ' WHERE '.$requirement;


            $db = Widget_Db::getInstance();
            if($qry = $db->query($sql)){
                $rs =  $db->fetch_array($qry);
                return intval($rs['count']);
            }
            return 0;
        }

        /**
         * @date 2012年4月15日13:46:24
         * @desc 判断这个orm_obj 是否有key这个属性 fields
         */
        public function keyExists($key){
            if(in_array($key, $this-> _fields)){
                return true;
            }else{
                return false;
            }
        }
        /**
         * 增加这个功能，便于计数
         * 
         * @param mixed $key
         */
        public function increase($key , $num = 1){

            if(!$this->keyExists($key)){
                return false;
            }
            $sql = "UPDATE ".$this->_table." SET $key = $key + $num WHERE " 
            . $this->_primary_key . " = " .$this->_values[$this->_primary_key];
            $db = Widget_Db::getInstance();
            return $db->query($sql);        
        }
        public function decrease($key){
            if(!$this->keyExists($key)){
                return false;
            }
            $sql = "UPDATE ".$this->_table." SET $key = $key - 1 WHERE " 
            . $this->_primary_key . " = "  .$this->_values[$this->_primary_key];
            $db = Widget_Db::getInstance();
            return $db->query($sql);  
        }

        public function toArray(){
            $array = array();
            if(count ($this -> _values) != 0)
                foreach($this -> _values as $key => $value){
                    $array[$key] = $value;
            }
            return $array;

        }


    }
?>