<?php
class Tiri_Router_Resolver implements Tiri_Interface_Resolver{

    private $cParamName;
    private $aParamName;
    private $defaultC;
    private $defaultA;

    public function __construct(){
        $this->cParamName = Tiri_Config::get('tiri.controllerParamName');
        $this->aParamName = Tiri_Config::get('tiri.actionParamName');
        $this->defaultC = Tiri_Config::get('tiri.defaultController');
        $this->defaultA = Tiri_Config::get('tiri.defaultAction');
    }

    public function getController(Tiri_Request $req){
        $controller = $this->defaultC;
        if($this->cParamName){
            $c = $req->getString($this->cParamName);
            if($c){
                $controller = $c;
            }
        }
        return 'Controller_'.$controller;
    }
    public function getAction(Tiri_Request $req){
        $action = $this->defaultA;
        if($this->aParamName){
            $a = $req->getString($this->aParamName);
            if($a){
                $action = $a;
            }
        }
        return $action . 'Action';
    }
    public function getUrl($controller, $action, $argv = array()){
        if(!is_array($argv)){
            $argv = array();
        }
        if(!$controller){
            $controller = $this->defaultC;
        }
        if(!$action){
            $action = $this->defaultA;
        }
        if($this->cParamName){
            $argv[$this->cParamName] = $controller;
        }
        if($this->aParamName){
            $argv[$this->aParamName] = $action;
        }


        $ret = 'index.php?';
        if($argv){
            foreach($argv as $key => $value){
                $ret  .= $key.'='.$value.'&';
            }
        }
        return substr($ret , 0 , (strlen($ret) - 1));
    } 
}