<?php
namespace Tiri\Widget;
class MCache {

    public static $instance;

    private $_memcLink;

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Widget_Mcache();
            if (!self::$instance) {
                return false;
            }
        }
        return self::$instance;
    }


    private function __construct() {
        $config = Tiri_Config::get('Widget_MCache');
        if (!$config) {
            return false;
        }
        if (is_string($config)) {
            $config = array($config);
        }

        $this->_memcLink = new Memcached();
        foreach ($config as $oneMemc) {
            if (false === ($_pos = strpos($oneMemc, ':'))) {
                $server = $oneMemc;
                $port = 11211;
            } else {
                $server = substr($oneMemc, 0, $_pos);
                $port = substr($oneMemc, $_pos + 1, strlen($oneMemc));
            }
            $this->_memcLink->addServer($server, $port);
            /** 一致性分布算法 */
            $this->_memcLink->setOption(Memcached::OPT_DISTRIBUTION, Memcached::DISTRIBUTION_CONSISTENT);
        }
    }

    public function set($key, $value, $expire) {
        /**
         * true of false
         */
        return $this->_memcLink->set($key, $value, $expire);
        //Log::info(__FILE__ , __LINE__ , 'MCache::set('.$key.')'.$value);

    }

    public function replace($key, $value, $expire) {
        return $this->_memcLink->replace($key, $value, $expire);
    }

    public function get($key) {

        return $this->_memcLink->get($key);
        // NK_Log::info(__FILE__ , __LINE__ , 'MCache::get('.$key.') = '.var_export($ret , true));


    }

    public function getMulti($keys) {

        return $this->_memcLink->getMulti($keys);
    }

    public function delete($key) {
        if (!$this->_memcLink->delete($key)) {
            $this->logError();
            return false;
        }
        return true;
    }

    public function increment($key, $value = 1, $expire = 0) {
        if (!$this->get($key)) {
            $this->set($key, 0, $expire);
        }
        return $this->_memcLink->increment($key, $value);
    }

    //* 减到0之后，将会消失，get不到了   */
    public function decrement($key, $value = 1) {
        if (!$this->get($key)) {
            return false;
        }
        return $this->_memcLink->decrement($key, $value);
    }

    public function whichServer($key) {
        $ret = $this->_memcLink->getServerByKey($key);
        return array('real' => implode(':', $ret));
    }

}