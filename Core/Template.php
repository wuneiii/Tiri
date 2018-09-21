<?php

namespace Sloop\Core;

use Sloop\Lib\Resource;

class Template {

    public static $instance;

    static public function getInstance() {
        if (null == self::$instance) {
            self::$instance = new Template();
        }
        return self::$instance;
    }


    private $tplData = array();

    private $tplRootPath = '';
    private $tplFileExt  = '';

    private $jsFileArray;
    private $jsKvArray;
    private $cssFileArray;

    private function __construct() {
        $config = Config::getInstance();
        $this->tplRootPath = $config->get('app.tplPath');
        $this->tplFileExt = $config->get('app.tplExt');
    }


    public function setRootPath($path) {
        if ($path) {
            Config::getInstance()->set('app.tplPath', $path);
            $this->tplRootPath = $path;
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
        if ($key == '' && count($this->jsKvArray) != 0) {
            echo <<<EOF
<script type="text/javascript">

EOF;
            foreach ($this->jsKvArray as $k => $v) {
                echo <<<EOF
var $k = '$v';

EOF;
            }
            echo <<<EOF
</script>

EOF;
        } else {
            $this->jsKvArray[$key] = $value;
        }

    }

    public function insertJsFile($file = '') {
        if ($file == '' && count($this->jsFileArray) != 0) {

            foreach ($this->jsFileArray as $file) {
                $fileFullPath = Resource::jsFilePath($file);
                echo <<<EOF
<script type="text/javascript" src="$fileFullPath"></script>

EOF;
            }
        } else {
            $this->jsFileArray[] = $file;
        }

    }

    public function insertCssFile($file = '') {
        if ($file == '' && count($this->cssFileArray) != 0) {

            foreach ($this->cssFileArray as $file) {
                $fileFullPath = Resource::cssFilePath($file);
                echo <<<EOF
<link rel="stylesheet" type="text/css"  href="$fileFullPath">\n\t
EOF;
            }
        } else {
            $this->cssFileArray[] = $file;
        }

    }

    public function insertJsCssFile() {
        $this->insertJsVal();
        $this->insertJsFile();
        $this->insertCssFile();
    }

    public function getTplRealFile($tplName) {
        if (substr($this->tplRootPath, -1) != '/') {
            $this->tplRootPath .= "/";
        }

        return $this->tplRootPath . $tplName . "." . $this->tplFileExt;
    }

    public function loadTpl($file) {

        if (!file_exists($file)) {
            return false;
        }
        $content = file_get_contents($file);
        $parserList = array(
            '\Template\TagUrl',
            '\Template\TagRender',
            '\Template\TagPhp',
        );
        foreach ($parserList as $p) {
            $parserFullName = __NAMESPACE__ . $p;
            if (!class_exists($parserFullName)) {
                continue;
            }

            $callback = array(
                new $parserFullName,
                'parse'
            );
            $content = call_user_func($callback, $content, $this->tplData);
        }

        return $content;
    }


    public function render($tplName) {

        $realTplFile = $this->getTplRealFile($tplName);
        $tplContent = $this->loadTpl($realTplFile);
        echo $tplContent;

    }
}