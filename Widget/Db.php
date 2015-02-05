<?php
namespace Tiri\Widget;
use Tiri\Config;
use Tiri\Widget\Db\Mysql;

class Db {

    /** 存放 一个具体的数据库驱动实例 */
    private static $_instance;

    private static $_driver = 'Mysql';

    private function __construct() {
    }

    static public function getInstance() {
        if (self::$_instance == null) {
            $self = new Db();
            self::$_instance = $self->init();
        }
        return self::$_instance;
    }

    /** 返回一个可供使用的 Widget_Db 对象  */
    private function init() {
        $conf = Config::get('Widget_Db');

        if (self::$_driver == 'Mysql') {

            return Mysql::getInstance($conf);

        } else {

            die('Error: 引擎 [' . $_driver . '] 尚未实现');

        }

    }

    static function fetch_assoc($query) {
        $query = mysql_fetch_row($query, MYSQL_ASSOC);
        return $query;
    }

    static function fetch_object($query) {
        $query = mysql_fetch_object($query);
        return $query;
    }

    static public function report() {
        Db::getInstance()->report();
    }

    static public function ping() {
        self::$_instance->ping();
    }
}