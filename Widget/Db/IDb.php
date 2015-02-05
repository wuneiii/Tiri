<?php
namespace Tiri\Widget\Db;
interface IDb {

    public static function getInstance($conf);

    public function getErrorNo();

    public function getErrorMsg();

    public function report();


}