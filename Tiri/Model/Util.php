<?php

class Tiri_Model_Util extends Tiri_Model {

    static public function mapTableToModel($table, $modelName) {
        if (class_exists($modelName)) {
            $model = Tiri_Model::factory($modelName);
            $model->setTable($table);
            return $model;
        } else {
            Tiri_Error::add('modeName 不存在[' . $modelName . ']', __FILE__, __LINE__);
        }


    }
}