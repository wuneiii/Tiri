<?php

namespace Sloop\Core;

use Sloop\Lib\Probe;

class Router {

    const ROUTER_CONTINUE = 1;

    const ROUTER_STOP = 2;

    public function addFixedRouter($controller, $action, $hookInFunc) {

        $this->fixedRouter[] = array($controller, $action, $hookInFunc);

    }

    private $resolver = null;

    private $fixedRouter = array();

    public function __construct(UrlResolverInterface $resolver) {
        $this->resolver = $resolver;
    }


    public function dispose() {

        $request = Request::getInstance();
        $ctlName = $this->resolver->getController($request);
        $actName = $this->resolver->getAction($request);

        /** 1step */
        /** run fixed router */

        if (count($this->fixedRouter) != 0) {
            foreach ($this->fixedRouter as $rule) {
                if (count($rule) != 3) continue;
                if ($rule[0] == $ctlName && $rule[1] == $actName) {
                    Probe::here('Before run fixed router;[' . $rule[0] . ' : ' . $rule[1] . ' -> ' . $rule[2] . ']');

                    $ret = call_user_func($rule[2]);
                    Probe::here('After run fixed router;');

                    if ($ret != self::ROUTER_CONTINUE) {
                        return;
                    }
                }
            }
        }

        Probe::here('Before run default router;[' . $ctlName . ' : ' . $actName . ']');

        $response = $this->runAction($ctlName, $actName, $request);

        Probe::here('After run default router;');

        return $response;

    }

    private function runCallback($callback) {

    }

    private function runAction($ctl, $act, $request) {

        if (class_exists($ctl)) {

            $ctlObject = new $ctl;
            if (method_exists($ctlObject, $act)) {

                return call_user_func(array($ctlObject, $act), $request);
            }

        }
        die(sprintf('[%s][%s]NOT EXIST', $ctl, $act));
    }
}