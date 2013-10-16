<?php
class Tiri_Template{

    private $_tpl_data = array();

    public static $js_file_array;

    public static $js_kv_array;

    private static $_instance;

    static public function getInstance(){
        if(NULL == self::$_instance){
            self::$_instance = new Tiri_Template();
        }
        return self::$_instance;
    }

    public function assign($key , $value){

        $this->_tpl_data[$key] = $value;

    }
    public function assignArray(array $vars){
        if (!is_array($vars))
            return;
        foreach($vars as $key => $value){
            $this->assign($key , $value);
        }
    }

    public function js_kv($key ='',$value =''){
        if($key == '' && count(self::$js_kv_array) !=0){
            echo <<<EOF
<script type="text/javascript">

EOF;
            foreach(self::$js_kv_array as $k => $v){
                echo <<<EOF
var $k = '$v';

EOF;
            }
            echo <<<EOF
</script>

EOF;
        }else{
            self::$js_kv_array[$key] = $value;
        }

    }
    public function js_file($file = ''){
        if($file == '' && count(self::$js_file_array)!= 0){

            foreach(self::$js_file_array as $file){
                $fileFullPath  = Widget_Resource::jsFile($file);
                echo <<<EOF
<script type="text/javascript" src="$fileFullPath"></script>

EOF;
            }
        }else{
            self::$js_file_array[] = $file;    
        }

    }
    public function js(){
        $this->js_kv();
        $this->js_file();

    }

    public function render($file){
        Widget_Probe::here('Before Tiri_Template::render('.$file.');');

        Tiri_Hook::getInstance()->runHook('beforeRender');

        $this -> renderWithoutHook($file);

        Tiri_Hook::getInstance()->runHook('afterRender');
        Widget_Probe::here('After Tiri_Template::render('.$file.');');
    }
    
    /**
    * @date 2013-10-15 17:39:44
    * @desc 渲染大括号模板
    * 
    * @param mixed $template
    */
    public function renderBraceTemplate($template){
        extract($this -> _tpl_data);
        if(count($this->_tpl_data) > 0)
        foreach($this->_tpl_data as $key => $value){
            $template = preg_replace('#{{' . $key . '}}#', $value, $template);    
        }
        echo $template;    
    }

    public function renderWithoutHook($file){

        extract($this -> _tpl_data);

        $file =  Tiri_App::getAppTemplatePath().$file;
        if(file_exists($file)){
            require_once $file ;
        }else{
            if(FW_DBUG){
                Func_Util::alert('template not exists:'.$file);
            }
        }
    }


}