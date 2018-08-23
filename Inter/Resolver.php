<?php

namespace Sloop\Inter;

use \Sloop\Core\Request;

interface Resolver {
    public function getController(Request $req);

    public function getAction(Request $req);

    public function getUrl($controller, $action, $argvs = array());
}
