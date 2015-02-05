<?php
namespace Tiri\UrlResolver;
use Tiri\Request;
use Tiri\Config;

class Resolver implements IResolver{

    private $cParamName;
    private $aParamName;
    private $defaultC;
    private $defaultA;

    public function __construct(){
        $this->cParamName = Config::get('tiri.controllerParamName');
        $this->aParamName = Config::get('tiri.actionParamName');
        $this->defaultC = Config::get('tiri.defaultController');
        $this->defaultA = Config::get('tiri.defaultAction');
    }

    public function getController(Request $req){
        $controller = $this->defaultC;
        if($this->cParamName){
            $c = $req->getString($this->cParamName);
            if($c){
                $controller = $c;
            }
        }
        return 'Controller_'.$controller;
    }
    public function getAction(Request $req){
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