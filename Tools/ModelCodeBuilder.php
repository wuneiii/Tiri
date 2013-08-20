<?php

    /** 
    * @note  连接数据库自动生成model 代码
    */
    class Tools_ModelCodeBuilder{
	static public $modelPath = '';

        /** Model Code Template */
        static private $_template ="<?php
        /**
        * Autogen @ %s
        * mapping to '%s' table of db 
        */
        class %s extends Tiri_Model{
            public function __construct(){
                \$this -> _table = '%s';
                \$this -> _primary_key = '%s';
                \$this -> _fields= array(\n%s);
            }
        }
        ?>";
        /** Model User Class Pash*/
        static private $_classPath = 'Model';

        /** $_cmd = 'OverWriteALL|GenNew';*/
        static private $_cmd;

        static public function writeToFile($className , $code){
            $file = __APP_ROOT__ .self::$modelPath. str_replace('_' , '/' , $className).'.php';

            if(file_exists($file)){
                if(self::$_cmd == 'GenNew'){
                    echo "Skip File : $file <br>"; 
                    return;
                }else{
                    rename ($file , $file.'.bak'.@date('Y-m-d-H-i-s').'.php');
                }
            }
            file_put_contents($file , $code);
            echo "Gen File : $file \t\tover;<br>";
        }


        static public function build($cmd = 'GenNew'){
            if(!in_array($cmd , array('OverWriteALL' , 'GenNew'))){
                die('Error : $cmd = OverWriteALL|GenNew;  defaule = GenNew' );
            }
            self::$_cmd = $cmd;



            Widget_Db::getInstance();

            $qry = mysql_query('show tables');
            while($rs = mysql_fetch_array($qry)){

                $tableName = $rs['0'];
                $td = mysql_query("desc $tableName");

                /** 处理一个表,并生成orm 代码 */
                $modelName = self::$_classPath . '_'. self::tableNameToModelName($rs[0]);
                $tableFields = '';
                while($rs = mysql_fetch_assoc($td)){
                    if($rs['Key'] == 'PRI'){
                        $tablePk = $rs['Field'];
                    }
                    $field = $rs['Field'];
                    $tableFields .= "                '$field',\n";
                }   

                $tableFields = substr($tableFields , 0, (strlen($tableFields) - 2));
                $code  = sprintf(self::$_template ,@date('Y-d-m H:i:s',time()) , $tableName , $modelName , $tableName, $tablePk, $tableFields );

                self::writeToFile($modelName , $code);
            }

            echo 'Gen Model Success. @'.@date('Y-m-d h:i:s' , time());

        }

        static public function tableNameToModelName($tableName){

            $arr  = explode('_' ,$tableName);
            foreach($arr as $key => $value){
                $arr[$key] = ucfirst($value);
            }
            $newTableName = implode('',$arr);
            return  $newTableName;
        }
    }
?>
