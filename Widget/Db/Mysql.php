<?php
    class Widget_Db_Mysql implements Widget_Db_Interface{

        private static $_instance;

        private $_link;

        private $_info;


        public static function getInstance($conf){
            if(self::$_instance == null){

                self::$_instance = new Widget_Db_Mysql();   
                self::$_instance -> connect($conf['dbHost'].':'.$conf['dbPort'] , $conf['dbUser'] , $conf['dbPassword'] , $conf['dbName']);
                if($conf['dbCharset'] != '')
                    self::$_instance -> query("SET NAMES ".$conf['dbCharset'].";");
            }
            return self::$_instance;
        }
        private function connect($servername, $dbusername, $dbpassword, $dbname) {

            if(($this ->_link = mysql_connect($servername, $dbusername, $dbpassword)) === false) {
                $this->halt("数据库出错");
            }

            if($dbname) {
                mysql_select_db($dbname , $this -> _link);
            }

        }
        function select_db($dbname) {
            return mysql_select_db($dbname);
        }
        function fetch_array($query, $result_type = MYSQL_ASSOC) {
            return mysql_fetch_array($query, $result_type);
        }
        function query($sql) {
            $_start = microtime(true);
            if(!($query = mysql_query($sql , $this -> _link))) {
                $this->halt('MySQL Query Error', $sql);
            }
            $_end = microtime(true);

            $this -> _info ['sql'][] = $sql;
            $this -> _info ['time'][] = $_end - $_start;


            return $query;
        }

        function start_transaction(){
            $this -> query('START TRANSACTION');
        }
        function commit_transaction(){
            $this -> query('COMMIT');
        }
        function rollback_transaction(){
            $this -> query('ROLLBACK');
        }


        function insert_id() {
            $id = mysql_insert_id();
            return $id;
        }


        function unbuffered_query($sql) {
            $query = $this->query($sql, 'UNBUFFERED');
            return $query;
        }



        function fetch_row($query) {
            $query = mysql_fetch_row($query);
            return $query;
        }
        function fetch_assoc($query) {
            $query = mysql_fetch_row($query , MYSQL_ASSOC);
            return $query;
        } 
        function fetch_object($query) {
            $query = mysql_fetch_object($query);
            return $query;
        }

        function fetch_one_array($query) {
            $result = $this->query($query);
            $record = $this->fetch_array($result);
            return $record;
        }

        function num_rows($query) {
            $query = mysql_num_rows($query);
            return $query;
        }

        function num_fields($query) {
            return mysql_num_fields($query);
        }

        function result($query, $row) {
            $query = @mysql_result($query, $row);
            return $query;
        }

        function free_result($query) {
            $query = mysql_free_result($query);
            return $query;
        }

        function version() {
            return mysql_get_server_info();
        }

        function close() {
            return mysql_close();
        }

        function halt($msg ,$sql =''){
            $message = "<html>\n<head>\n";
            $message .= "<meta content=\"text/html; charset=utf-8\" http-equiv=\"Content-Type\">\n";
            $message .= "<style type=\"text/css\">\n";
            $message .=  "body,td,p,pre {\n";
            $message .=  "font-family : Verdana, sans-serif;font-size : 12px;\n";
            $message .=  "}\n";
            $message .=  "</style>\n";
            $message .= "</head>\n";
            $message .= "<body bgcolor=\"#FFFFFF\" text=\"#000000\" link=\"#006699\" vlink=\"#5493B4\">\n";

            $message .= "<p></p><pre><b>".htmlspecialchars($msg)."</b></pre>\n";
            $message .= "<b>Mysql error description</b>: ".$this->geterrdesc()."\n<br />";
            $message .= "<b>Mysql error number</b>: ".$this->geterrno()."\n<br />";
            $message .= "<b>Date</b>: ".date("Y-m-d @ H:i")."\n<br />";
            $message .= "<b>Script</b>: http://".$_SERVER['HTTP_HOST'].getenv("REQUEST_URI")."\n<br />";
            $message .= "<b>SQL</b>: ".$sql."\n<br />";
            $message .= "</body>\n</html>";
            echo $message;
            exit;
        }
        /**
         * @since 2011-9-13 1:26:19
         * 
         */
        function get_count_by_sql($sql){
            $qry = $this->query($sql);
            return $this->num_rows($qry);
        }
        function runCountSql($sql){
            $qry = $this -> query($sql);
            $rs  = $this -> fetch_array($qry , MYSQL_ASSOC);
            return $rs['count'];
        }


        public function getErrorNo(){
            return mysql_errno();
        }
        public function getErrorMsg(){
            return mysql_error();
        }


        function geterrdesc() {
            return mysql_error();
        }

        function geterrno() {
            return intval(mysql_errno());
        }



        function report(){
            if(count($this -> _info['sql']) == 0 ){
                return ;
            }
            echo '<div><p>Core-Framework Sql Track:</p><ol>';

            foreach($this -> _info['sql'] as $k => $sql){ 
                $_td = sprintf('%.4f' , $this -> _info['time'][$k]) ;             

                echo '<li>['. $_td .'s]'.$sql.'</li>';
            }
            echo '</ol><div>';
        }
        function ping(){
            mysql_ping($this->_link);
        }
    }
