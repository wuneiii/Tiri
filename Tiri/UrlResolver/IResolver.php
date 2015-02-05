<?php
namespace Tiri\UrlResolver;
use Tiri\Request;

interface IResolver {
    public function getController(Request $req);

    public function getAction(Request $req);

    public function getUrl($controller, $action, $argv = array());
}
