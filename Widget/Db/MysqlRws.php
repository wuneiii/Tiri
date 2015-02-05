<?php
namespace Tiri\Widget\Db;
use Tiri\Config;
use Tiri\Log;


class MysqlRws {
    const SQL_TYPE_READ = 1;
    const SQL_TYPE_WRITE = 2;
    const SQL_TYPE_UNKNOWN = 3;

    public static $instance;

    private $writeMysql;
    private $readMysql;

    // TODO:
    private $rwsSwitch = true;


    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new MysqlRws();
        }
        return self::$instance;
    }


    private function __construct() {
        // 默认读写分离开关开。
        $this->rwsSwitch = true;

        $dbConf = Config::get('Widget_Db');
        if ($link = $this->getConnect($dbConf)) {
            $this->writeMysql = $link;
        } else {
            $this->halt('connect error');
        }
        $dbSlaveConf = Config::get('Widget_Db_Slave');
        if ($dbSlaveConf && count($dbSlaveConf) > 0) {
            $dbConf = $dbSlaveConf[mt_rand(0, (count($dbSlaveConf) - 1))];
            if ($link = $this->getConnect($dbConf)) {
                $this->readMysql = $link;
            } else {
                $this->halt('connect slave error');
            }
        }
    }

    // 打开和关闭读写分离的开关
    public function setRwsSwitch($bool) {
        $this->rwsSwitch = boolval($bool);
    }

    public function getConnect($dbConf) {
        if (!$dbConf)
            return false;
        if (!$dbConf['dbPort']) {
            $dbConf['dbPort'] = 3306;
        }
        if (!$dbConf['dbCharset']) {
            $dbConf['dbCharset'] = 'utf8';
        }
        $link = mysqli_connect($dbConf['dbHost'], $dbConf['dbUser'],
            $dbConf['dbPassword'], $dbConf['dbName'], $dbConf['dbPort']);
        if (!$link) {
            return false;
        }
        mysqli_query($link, "SET NAMES " . $dbConf['dbCharset']);
        return $link;
    }

    public function query($sql) {
        $type = self::getSqlType($sql);
        switch ($type) {
            case self::SQL_TYPE_READ:
                Log::debug(__file__, __line__, '[读][' . var_export($this->readMysql->host_info, true) . ']' . $sql);
                return mysqli_query($this->readMysql, $sql);

            case self::SQL_TYPE_WRITE:
            case self::SQL_TYPE_UNKNOWN:
            default:
                Log::debug(__file__, __line__, '[写][' . var_export($this->writeMysql->host_info, true) . ']' . $sql);
                return mysqli_query($this->writeMysql, $sql);
        }
    }

    public function num_rows($qry) {
        return mysqli_num_rows($qry);
    }

    public function fetch_array($qry) {
        return mysqli_fetch_array($qry);
    }

    public function insert_id() {
        return mysqli_insert_id($this->writeMysql);
    }


    private function getSqlType($sql) {
        $map = array(
            '#^select#i' => self::SQL_TYPE_READ,
            '#^(update|insert|delete|replace)#i' => self::SQL_TYPE_WRITE,
        );
        foreach ($map as $p => $type) {
            if (preg_match($p, $sql)) {
                return $type;
            }
        }
        return self::SQL_TYPE_UNKNOWN;
    }

    public function halt($msg) {
        die('mysql halt : ' . $msg);
    }


}