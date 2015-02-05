<?php
namespace Tiri;
use Tiri\Widget\Db;
/**
 * 1.数据表必须用一个auto_inc 的主键，否则不能支持
 * 2.
 * Class Tiri_Model
 */
class Model {

    /**
     * @desc  table name in db
     * */
    protected $_table;

    /**
     * @desc all fields name in this table
     * */
    protected $_fields = array();

    /**
     * @desc value of fields
     */
    protected $_values = array();

    /**
     * @var string
     */
    protected $_primary_key = 'id';

    /**
     * 保存字段外的信息, where order limit 。
     * @var
     */
    protected $_condition = array();

    /**
     * 标识是否为空
     * 任何一个set操作都会设置这个变量
     * @var bool
     */
    protected $_isEmpty = true;


    public function __get($key) {
        if (in_array($key, $this->_fields) && isset($this->_values[$key])) {
            return $this->_values[$key];
        }
        return null;
    }

    public function __set($key, $value) {
        if (in_array($key, $this->_fields)) {
            $this->_isEmpty = false;
            $this->_values[$key] = $value;
            return true;
        }
        return false;
    }

    /**
     * 给主键赋值
     * @param $id
     */
    public function setId($id) {
        $this->_values[$this->_primary_key] = $id;
    }

    /**
     * 获取主键的值
     * @return mixed
     */
    public function getId() {
        return $this->_values[$this->_primary_key];

    }

    /**
     * 设置tableName。少数情况下，可能会动态生成model实例
     * @param $tableName
     */
    public function setTable($tableName) {
        $this->_table = $tableName;
    }

    /**
     * 保存order值
     * @param $order
     */
    public function setOrder($order) {
        $this->_condition['order'] = $order;
    }

    public function setLimit($limit) {
        $this->_condition['limit'] = $limit;
    }

    public function setWhere($where) {
        $this->_condition['where'] = $where;
    }

    /**
     * 清空一个model对象
     */
    public function enEmpty() {
        $this->_values = array();
        $this->_isEmpty = true;
    }

    /**
     * 返回model是否为空
     * @return bool
     */
    public function isEmpty() {
        return $this->_isEmpty;
    }


    /**
     * model被model_set 的add 方法调用时用到
     * @return array
     */
    public function beAddedToSet() {
        return $this->_values;
    }

    /**
     * 从model_set 中取出来的时候.和上边的函数配对使用，基本被model_set用到
     * @param $modelName
     * @param $_values
     * @return mixed
     */
    public static function beReturnFromSet($modelName, $_values) {
        $modelInstance = new $modelName;
        $modelInstance->fill($_values);
        return $modelInstance;
    }


    /**
     * 填充一个对象
     */
    public function fill() {
        $argv = func_get_args();
        $argc = count($argv);
        if (!$argv) {
            return;
        }
        if ($argc == 1 && is_array($argv[0])) {
            $this->fillFromArray($argv[0]);
        } else if ($argc == 2 && is_string($argv[0])) {
            $this->fillFromKv($argv[0], $argv[1]);
        } else if ($argc == 1 && $argv[0] instanceof Tiri_Model) {
            $model = $argv[0];
            foreach ($this->_fields as $f) {
                if ($v = $model->getField($f)) {
                    $this->fillFromKv($f, $v);
                }
            }
        }
    }

    /**
     * 用key value 对填充对象
     *
     * @param $key
     * @param $value
     * @return bool
     */
    public function fillFromKv($key, $value) {
        if (in_array($key, $this->_fields)) {
            $this->_values[$key] = $value;
            return true;
        }
        return false;
    }


    public function getField($key) {
        if (!$key) {
            return null;
        }
        if ($this->keyExists($key) && isset($this->_values[$key])) {
            return $this->_values[$key];
        }
    }


    /*
     * 用数组填充对象
     */
    public function fillFromArray($array) {
        if (!is_array($array) || !$array)
            return;
        foreach ($array as $k => $v) {
            $this->fillFromKv($k, $v);
        }
    }


    /**
     * 查找所有符合条件的.失败返回空model_set
     * 包含所有字段条件，setXX增加的order，where，limit函数都等
     * @return Model\Set
     */

    public function findAllMatch() {
        $sql = sprintf("SELECT * FROM `%s`", $this->_table);

        // where
        if ($this->_values) {
            $where = array();
            foreach ($this->_values as $k => $v) {
                if ($v === null)
                    continue;
                $where[] = " `$k` ='$v' ";
            }
            if ($this->_condition['where']) {
                $where[] = $this->_condition['where'];
            }
            if ($where) {
                $sql .= " WHERE " . implode(' AND ', $where);
            }
        }
        //order
        if (!$order = $this->_condition['order']) {
            $order = " ORDER BY " . $this->_primary_key . " DESC ";
        }
        //limit
        if (!$limit = $this->_condition['limit']) {
            $limit = '';
        }
        //final sql
        $sql .= " $order $limit";

        return $this->getModelSetBySql($sql);
    }

    /**
     * 内部使用，给一个sql，返回一个model_set
     *
     * @note 最好在内部使用，因为内部生成的sql查到的字段，都能用本model来保存。\
     *       如果让外部自由传入sql，可能会查到不包含在本model中的字段，model会丢弃那些数据
     *
     * @param $sql
     * @return Model\Set
     */
    protected function getModelSetBySql($sql) {
        $modelSet = new Model\Set();

        $db = Db::getInstance();
        $qry = $db->query($sql);
        if ($qry)
            while ($rs = $db->fetch_array($qry)) {
                $modelSet->add($this->cloneSelfWithData($rs));
            }
        return $modelSet;
    }

    /**
     * 返回一个Model 无论是否为空
     * @param $sql
     * @return mixed
     */
    protected function getModelBySql($sql) {
        $model = $this->cloneSelfWithData(array());

        $db = Db::getInstance();
        $qry = $db->query($sql);
        if ($qry && $rs = $db->fetch_array($qry)) {
            $model->fillFromArray($rs);
        }
        return $model;
    }

    /**
     * 克隆一个自己，产生一个新的同类型的model，如果给就带数据
     * @param array $data
     * @return mixed
     */
    public function cloneSelfWithData($data = array()) {
        // TODO::把model加入modelSet 实际是一个序列化的过程，把model变成了value数组
        // 如果数据多，很浪费空间
        $modelName = get_class($this);
        $modelInstance = new $modelName;
        if ($data) {
            call_user_func_array(array($modelInstance, 'fill'), $data);
        }
        return new $modelInstance;
    }


    /**
     * 按照orderField 排序查询，最后一条加载入model。失败返回false
     * @param $orderField
     * @return bool|Model
     */
    public function loadLastMatch($orderField) {
        if (!$field = $this->keyExists($orderField)) {
            $field = $this->_primary_key;
        }
        $this->setOrder(' order by ' . $field . ' desc ');
        $this->limit(' limit 1 ');
        $modelSet = $this->findAllMatch();
        if ($modelSet->total() == 1) {
            $this->fill($modelSet->getNext());
            return true;
        }
        return false;
    }


    /***
     * @return array(ojb)
     * @input
     * @desc 主要用来显示列表页面
     * @desc fetchAll方法改名为pager ，专为pager服务，查询统一到上边的getAllMatch
     */
    public function pager($conf = array('start' => 0, 'limit' => 0, 'orderField' => '', 'orderType' => 'DESC', 'where' => '')) {

        if ($conf['orderField'] == '') {
            $conf['orderField'] = $this->_primary_key;
        }
        if ($conf['limit'] == '0') {
            $conf['limit'] = Tiri_Config::get('per_page');
        }
        if ($conf['orderType'] == '') {
            $conf['orderType'] = 'desc';
        }


        return $this->getAllMatch(
            " order by " . $conf['orderField'] . " " . $conf['orderType'] . "  ",
            " limit " . $conf['start'] . ',' . $conf['limit'],
            $conf['where']);

    }

    /**
     * 根据主键加载一个对象，如果加载失败，返回false
     * @param $pk
     * @return bool|mixed
     */
    public function loadByPk($pk) {
        if (!$pk || !$this->_primary_key) {
            return false;
        }
        $sql = sprintf("SELECT * FROM `%s` WHERE `%s`='%s'",
            $this->_table, $this->_primary_key, $pk);
        $model = $this->getModelBySql($sql);
        if ($model->isEmpty()) {
            return false;
        }
        $this->fill($model);
        return true;
    }

    /**
     * 根据key value 对来加载数据
     * @param $key
     * @param $value
     * @return bool
     */
    public function loadByUniqueKey($key, $value) {
        if (!$key) {
            return false;
        }
        if ($this->keyExists($key)) {
            $this->fillFromKv($key, $value);
            if ($this->loadByMatch()) {
                return true;
            }
        }
        return false;
    }

    /**
     * 通过条件匹配，来加载对象
     * @return bool
     */
    public function loadByMatch() {
        $this->setLimit(' limit 1 ');
        $modelSet = $this->findAllMatch();
        if ($modelSet->total() == 1) {
            $this->fill($modelSet->getNext());
        }
        return false;
    }

    /**
     * 根据主键删除. 不接受model内部的参数，只接受函数明确的参数
     * @param string $pk
     * @return bool|resource
     */
    public function deleteByPk($pk) {
        if (!$pk || !$this->_primary_key) {
            return false;
        }

        $sql = "DELETE  FROM `%s` WHERE `%s` = '%s'";
        $sql = sprintf($sql, $this->_table, $this->_primary_key, $pk);

        if ($this->execQuery($sql)) {
            return true;
        }
        return false;
    }


    /**
     * 通过匹配条件删除
     * @return bool
     */
    public function deleteByMatch() {
        $sql = "DELETE  FROM `" . $this->_table . "` ";

        // where
        $where = array();
        if ($this->_values) {
            foreach ($this->_values as $k => $v) {
                if ($v === null)
                    continue;
                $where[] = " `$k` ='$v' ";
            }
            if ($this->_condition['where']) {
                $where[] = $this->_condition['where'];
            }
            if ($where) {
                $sql .= " WHERE " . implode(' AND ', $where);
            }
        }
        if (!$where) {
            return false;
        }
        if ($this->execQuery($sql)) {
            return true;
        }
        return false;
    }


    /**
     * 按唯一字段删除记录
     * @param $key
     * @param $value
     * @return bool
     */
    public function deleteByUniqueKey($key, $value) {
        if (!$key || !$value || !$this->keyExists($key)) {
            return false;
        }
        $this->setWhere('');
        $this->fillFromKv($key, $value);
        if ($this->deleteByMatch()) {
            return true;
        }
        return false;
    }

    /**
     * 尝试插入一条指定主键的记录。
     *
     * @param $pk
     * @return bool
     */
    public function lockPk($pk) {
        if (!$pk || !$this->_primary_key) {
            return false;
        }
        $sql = sprintf("INSERT INTO %s (%s) VALUES ('%s')", $this->_table, $this->_primary_key, $pk);
        if ($this->execQuery($sql)) {
            return true;
        }
        return false;
    }

    /**
     * 保存一个model到数据库中。如果主键被赋值，则更新记录。如果主键未被赋值，则新建记录。
     * @return bool|resource
     */
    public function saveToDb() {

        // 没有值
        if (!$this->_values) {
            return false;
        }

        $pk = $this->_primary_key;
        // 如果主键存在，使用update,不存在用insert

        if (isset($this->_values[$pk]) && $this->_values[$pk] !== null) {
            // update
            $pkValue = $this->_values[$pk];
            if (!$this->isPkExists($pkValue)) {
                if (!$this->lockPk($pkValue)) {
                    return false;
                }
            }
            $tpl = "UPDATE `%s` SET %s WHERE `%s` = '%s'";
            $setFields = array();
            foreach ($this->_values as $k => $v) {
                if ($k == $pk) {
                    continue;
                }
                $setFields[] = sprintf("`%s`='%s'", $k, $v);
            }
            $sql = sprintf($tpl, $this->_table, implode(',', $setFields), $pk, $pkValue);
        } else {
            // insert
            /** 主键没有 */;
            $fields = $values = array();
            foreach ($this->_values as $k => $v) {
                if ($this->_values[$k] === null) {
                    continue;
                }
                $fields[] = "`$k`";
                $values[] = "'$v'";
            }
            $tpl = "INSERT INTO `%s` (%s) VALUE (%s);";
            $sql = sprintf($tpl, $this->_table, implode(',', $fields), implode(',', $values));
            $sqlType = 'insert';
        }

        if ($insertId = $this->execQuery($sql)) {
            if ($sqlType == 'insert') {
                return $insertId;
            }
            return true;
        }
        return false;
    }


    /**
     * 按条件统计总量
     * @return int
     */
    public function getTotalNum() {

        $sql = sprintf("SELECT COUNT(%s) as count FROM `%s`",
            $this->_primary_key, $this->_table);

        // where
        if ($this->_values) {
            $where = array();
            foreach ($this->_values as $k => $v) {
                if ($v === null)
                    continue;
                $where[] = " `$k` ='$v' ";
            }
            if ($this->_condition['where']) {
                $where[] = $this->_condition['where'];
            }
            if ($where) {
                $sql .= " WHERE " . implode(' AND ', $where);
            }
        }
        if($qry = $this->execQuery($sql)){
            $rs = Db::getInstance()->fetch_array($qry);
            return intval($rs['count']);
        }
        return 0;
    }

    /**
     * 判断model是否具有这个字段
     * @param $key
     * @return bool
     */
    public function keyExists($key) {
        if (in_array($key, $this->_fields)) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * 探测某个主键是否已经占用
     * @param $pk
     * @return bool
     */
    public function isPkExists($pk) {
        if (!$pk || !$this->_primary_key) {
            return false;
        }
        $pk = $this->_primary_key;
        $sql = sprintf("SELECT %s FROM `%s` WHERE %s = '%s'",
            $this->_primary_key, $this->_table, $this->_primary_key, $pk);
        if ($this->getModelBySql($sql)) {
            return true;
        }
        return false;
    }


    /**
     * 单字段做加法
     * @param $key
     * @param int $num
     * @return bool|resource
     */
    public function increase($key, $num = 1) {

        if (!$this->keyExists($key)) {
            return false;
        }
        $sql = "UPDATE " . $this->_table . " SET $key = $key + $num WHERE "
            . $this->_primary_key . " = " . $this->_values[$this->_primary_key];

        return $this->execQuery($sql);
    }

    /**
     * 单字段做减法
     * @param $key
     * @return bool|resource
     */
    public function decrease($key) {
        if (!$this->keyExists($key)) {
            return false;
        }
        $sql = "UPDATE " . $this->_table . " SET $key = $key - 1 WHERE "
            . $this->_primary_key . " = " . $this->_values[$this->_primary_key];
        return $this->execQuery($sql);

    }

    public function execQuery($sql) {
        return Db::getInstance()->query($sql);
    }

    /**
     * 把一个model对象变成数组结构
     * @return array
     */
    public function toArray() {
        $array = array();
        if (count($this->_values) != 0)
            foreach ($this->_values as $key => $value) {
                $array[$key] = $value;
            }
        return $array;
    }


    /**
     * @desc for debug
     * */
    public function dump() {
        var_dump($this->_values);
    }
}