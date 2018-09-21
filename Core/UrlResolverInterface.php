<?php

namespace Sloop\Core;


interface UrlResolverInterface {
    public function getController(Request $req);

    public function getAction(Request $req);

    public function getUrl($controller, $action, $param = array());
}
