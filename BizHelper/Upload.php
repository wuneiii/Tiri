<?php

namespace Sloop\BizHelper;

class Upload {
    /**
     * 创建目标路径
     * 检查扩展名合法
     *
     * @param mixed $file $_FILES['name']
     * @param mixed $toDir
     */
    static function upload($file, $toDir, $desFileName = '') {
        if (empty($file)) {
            return false;
        }
        if (!file_exists($toDir)) {
            if (!mkdir($toDir, '0755', true)) {
                return false;
            }
        }
        $file_ext = Func_Util::getFileExt($file['name']);
        $valid_ext = C('validExt');
        if (is_array($valid_ext)) {
            if (!in_array($file_ext, $valid_ext)) {
                Tiri_Error::add('后缀名不符合要求[' . $file_ext . ']', __FILE__, __LINE__);
                return false;
            }
        }
        $tempName = $file["tmp_name"];

        $extend = pathinfo($file["name"]);
        $extend = strtolower($extend["extension"]);

        if ($desFileName == '') {
            $desFileName = @date("Ymd_His_", time()) . rand() . '.' . $extend;
        }

        $newName = $desFileName;
        if (!copy($tempName, $toDir . $newName)) {

            return false;
        }
        return $newName;
    }
}


?>
