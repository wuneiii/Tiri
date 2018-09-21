<?php

namespace Sloop\BizHelper;
/**
 * 组件功能：处理用户行为(不是做用户管理的)
 * 一个给定的Model就是一类角色用户，本类不支持‘角色’
 */

/**
 * @date 2012年12月2日17:47:34
 * @desc 0.3版本基本定稿
 *
 */
class User {

    /** User Model 的实例*/
    private $_userModel;

    private $_id;

    private $_logined;

    /** case1: 前台用户和后台管理员，同时使用Widget_User类
     *          就需要区分cookie名称，而一个Model对应一种类型的用户，用Model名来做cookie名比较合适
     *          也可以在init中由用户指定，不过就要就要一个config 项，未来可以考虑
     */
    private $_cookeName;
    /** 当前请求肯定是代表一个用户，故User全局单例 */
    private static $_instance;

    /** 单例 */
    static public function getInstance() {
        if (NULL == self::$_instance) {
            self::$_instance = new User();
        }
        return self::$_instance;
    }

    /** 有些地方想直接获取username 之类的信息，方便使用*/
    public function __get($key) {
        return $this->_userModel->$key;
    }

    public function __set($key, $value) {
        $this->_userModel->$key = $value;
    }


    /** App::init 中调用 ,用来做初始化，如果已经登录，会加载上次的状态，如果未登录，不动作*/
    public function init($modelName) {
        $m = $this->_userModel = Tiri_Model::factory($modelName);
        if ($m->keyExists('username') && $m->keyExists('password')) {

            /** ok,设置了Model ，Widget_User 既可以使用了 */
            $this->_cookeName = $modelName;
            $this->_logined = false;
        } else {
            /** 需要处理成统一错误处理函数 */
            die('Wiget_User setModel Function Error');
        }

        $cookie = unserialize(Widget_Cookie::get($this->_cookeName));
        if ($cookie['id'] != '' && $cookie['username'] != '') {
            $this->_userModel->loadById($cookie['id']);
            $this->_logined = true;
        }
    }

    /** 修改完用户信息，刷新一下当前User实例中的信息 */
    public function refresh() {
        if ($this->isLogined()) $this->_userModel->loadById();
    }

    /**
     * @return Model_User
     */
    public function isLogined() {
        return $this->_logined;
    }

    public function getCurUserModel() {
        return $this->_userModel;
    }

    public function getCurUserId() {
        return $this->_userModel->getId();
    }

    /**
     * put your comment there...
     *
     * @param mixed $username
     * @param mixed $password
     * @return bool
     */
    public function auth($username, $password) {
        if (empty($username) || empty($password)) {
            return false;
        }

        $m = $this->_userModel;
        $m->setEmpty();
        $m->username = $username;
        $m->password = self::encryptPassword($username, $password);
        $m->enable = 1;
        $cnt = $m->getTotalNums();
        if ($cnt == 1) {
            //$this -> _userModel -> loadByMatch();
            return true;
        }
        return false;
    }

    public function login($username, $password, $loginLongTime = false) {
        if (true === $this->auth($username, $password)) {
            $this->_userModel->loadByMatch();

            $this->_userModel->logintimes += 1;
            $this->_userModel->lastlogin = time();
            $this->_userModel->lastip = Tiri_Request::getIp();

            $this->_userModel->save_to_db();

            $this->_logined = true;
            $cookie = array('id'       => $this->_userModel->getId(),
                            'username' => $this->_userModel->username
            );
            /** cookie时间长短 */
            $expire = $loginLongTime == 1 ? 86400 * 30 : 86400;

            Widget_Cookie::set($this->_cookeName, serialize($cookie), $expire);
            return true;
        } else {
            return false;
        }
    }

    public function logout() {
        $this->_userModel->setEmpty();
        Widget_Cookie::delete($this->_cookeName);
    }

    /** 必须由当前的User来修改 */
    public function changePassword($oldPass, $newPass) {
        /**登陆才可修改*/
        if ($this->isLogined()) {
            $encryptPass = self::encryptPassword($this->_userModel->username, $this->_userModel->password);

            /** 校验原有密码*/
            if ($encryptPass == $this->_userModel->password) {
                $curUserId = $this->_userModel->getId();
                $newPass = self::encryptPassword($this->_userModel->username, $newPass);

                /** 为了防止这里sql过大，影响不先关字段，empty了*/
                $this->_userModel->setEmpty();
                $this->_userModel->setId($curUserId);
                $this->_userModel->password = $newPass;
                if ($this->_userModel->save_to_db()) {

                    /** 修改完成后，恢复原有数据结构*/
                    $this->_userModel->loadById($curUserId);
                    return true;
                }
                return false;
            }
            return false;
        }
        die('no login;so cant changepassword;');
    }

    /** 这个动作和当前User没绝对关系，故静态  下同*/
    public function addUser($username, $password) {
        if ($username == '') {
            die('用户名为空');
        }
        if ($password == '') {
            die('密码为空');
        }
        $user = $this->_userModel;
        $user->setEmpty();
        $user->fillFromArray($_POST);
        $user->username = $username;
        $user->password = $password;
        $user->enable = 1;
        $user->regtime = time();
        $user->password = self::encryptPassword($username, $password);
        if (false !== ($user_id = $user->save_to_db())) {
            return $user_id;
        }
        return false;
    }

    public function updateUser($id) {
        if (intval($id) == 0) {
            return false;
        }
        $user = $this->_userModel;
        $user->setEmpty();

        $user->loadById($id);
        $oldPass = $user->password;
        $user->fillFromArray($_POST);
        if ($user->password == '') {
            $user->password = $oldPass;
        } else {
            $user->password = self::encryptPassword($user->username, $user->password);
        }
        return $user->save_to_db();
    }

    public static function delUser() {

    }

    public static function disableUser($id) {

    }

    public static function enableUser($id) {

    }

    public static function encryptPassword($username, $password) {
        return md5($password . '@#$' . $username);
    }


    public function isExist($username) {
        $modelUser = $this->_userModel;
        $modelUser->setEmpty();

        $modelUser->username = $username;
        $cnt = $modelUser->getTotalNums();
        return $cnt == 1;
    }
}