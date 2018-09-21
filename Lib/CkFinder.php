<?php   
    class Widget_CkFinder{

        private $_assets;
        private $_setting ;

        public function __construct(){
            $this -> _assets = Tiri_Request::getInstance() -> getPath() .'assests/';
        }

        public function _get($key){
            return $this -> _setting($key);
        }
        public function _set($key , $value){
            $this -> _setting[$key] = $value;
        }
        public function set($key , $value){
            $this -> _setting[$key] = $value;
        }

        public function create($name){
            $class = $this -> _setting['class'];
            $value = $this -> _setting['value'];
            $ret = <<<FCK
        <script src="$this->_assets/ckfinder/ckfinder.js"></script>
        <script type="text/javascript">
            function __CkFinder__BrowseServer()
            {
                var finder = new CKFinder();
                finder.basePath = '$this->_assets/ckfinder/';    // The path for the installation of CKFinder (default = "/ckfinder/").
                finder.selectActionFunction = SetFileField;
                finder.popup();
            }

            // This is a sample function which is called when a file is selected in CKFinder.
            function SetFileField( fileUrl )
            {
                document.getElementById( '$name' ).value = fileUrl;
            }

        </script>

        <input id="$name" name="$name" type="text" size="60" class="$class" value="$value" />
        <input type="button" value="浏览服务器" onclick="__CkFinder__BrowseServer();" />           



            
FCK;
            echo $ret;

        }

    }
?>