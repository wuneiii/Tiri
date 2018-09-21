<?php
namespace Sloop\Lib\Html;

class FckEditor {

    private $_assets;
    private $_setting;

    public function __construct() {
        $this->_assets = Tiri_Request::getInstance()->getPath() . 'assests/';

    }

    public function _get($key) {
        return $this->_setting($key);
    }

    public function _set($key, $value) {
        $this->_setting[$key] = $value;
    }

    public function create($name) {
        $ret = <<<FCK
            
            <script src="$this->_assets/fckeditor/ckeditor.js"></script>
            <script src="$this->_assets/ckfinder/ckfinder.js"></script>
            <textarea class="ckeditor" name="$name" id="$name">$this->content</textarea>
            <script type="text/javascript">
            var editor = CKEDITOR.replace( '$name' );
                        CKFinder.setupCKEditor( editor, '$this->_assets/ckfinder/' ) ;
            </script>

            
FCK;
        echo $ret;

    }

}

?>
