<?php

namespace Sloop\Core;

use Sloop\Core\Router\Resolver;
use Sloop\Widget\Probe;
use Sloop\Widget\Resource;

class Template {

    private $tplData = array();

    public static $js_file_array;
    public static $js_kv_array;
    public static $css_file_array;
    public static $instance;

    private function __construct() {}

    static public function getInstance($path = '') {
        if (null == self::$instance) {
            self::$instance = new Template();
            if ($path != '') {
                self::$instance->setRootPath($path);
            }
        }
        return self::$instance;
    }


    public function setRootPath($path){
        if($path){
            Config::set('app.tplPath', $path);
        }
    }


    public function assign($key, $value) {
        $this->tplData[$key] = $value;
    }

    public function assignArray(array $vars) {
        if (!is_array($vars)) return;
        foreach ($vars as $key => $value) {
            $this->assign($key, $value);
        }
    }

    public function insertJsVal($key = '', $value = '') {
        if ($key == '' && count(self::$js_kv_array) != 0) {
            echo <<<EOF
<script type="text/javascript">

EOF;
            foreach (self::$js_kv_array as $k => $v) {
                echo <<<EOF
var $k = '$v';

EOF;
            }
            echo <<<EOF
</script>

EOF;
        } else {
            self::$js_kv_array[$key] = $value;
        }

    }

    public function insertJsFile($file = '') {
        if ($file == '' && count(self::$js_file_array) != 0) {

            foreach (self::$js_file_array as $file) {
                $fileFullPath = Resource::jsFilePath($file);
                echo <<<EOF
<script type="text/javascript" src="$fileFullPath"></script>

EOF;
            }
        } else {
            self::$js_file_array[] = $file;
        }

    }

    public function insertCssFile($file = '') {
        if ($file == '' && count(self::$css_file_array) != 0) {

            foreach (self::$css_file_array as $file) {
                $fileFullPath = Resource::cssFilePath($file);
                echo <<<EOF
<link rel="stylesheet" type="text/css"  href="$fileFullPath">
EOF;
            }
        } else {
            self::$css_file_array[] = $file;
        }

    }

    public function js() {
        $this->insertJsVal();
        $this->insertJsFile();
        $this->insertCssFile();

    }

    public function render($file, $absPath = false) {


        Probe::here('Before Tiri_Template::render(' . $file . ');');

        $this->renderWithoutHook($file, $absPath);

        Probe::here('After Tiri_Template::render(' . $file . ');');
    }

    /**
     * @date 2013-10-15 17:39:44
     * @desc 渲染大括号模板
     *
     * @param mixed $template
     */
    public function renderBraceTemplate($template) {
        extract($this->tplData);
        if (count($this->tplData) > 0) foreach ($this->_tpl_data as $key => $value) {
            $template = preg_replace('#{{' . $key . '}}#', $value, $template);
        }
        echo $template;
    }


    public function renderWithoutHook($file, $absPath = false) {
        $tplRootPath = Config::get('app.tplPath');
        if(substr($tplRootPath, strlen($tplRootPath)-1) != '/'){
            $tplRootPath .="/";
        }

        if (!$absPath) {
            $file = $tplRootPath . $file . ".".Config::get('app.tplExt');
        }
        extract($this->tplData);
        if (file_exists($file)) {
            require_once $file;
        } else {
            echo 'file {' . $file . '} not exists';
        }


    }
}