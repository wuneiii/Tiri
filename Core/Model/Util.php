<?php

namespace Sloop\Core\Model;

class Util extends \Sloop\Core\Model {

    static public function mapTableToModel($table, $modelName) {
        if (class_exists($modelName)) {
            $model = \Sloop\Core\Model::factory($modelName);
            $model->setTable($table);
            return $model;
        } else {
            Tiri_Error::add('modeName 不存在[' . $modelName . ']', __FILE__, __LINE__);
        }


    }
}

