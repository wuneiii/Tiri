<?php
namespace Tiri;
class ClassLoader {
    public static function register() {
        $callback = array(new ClassLoader(), 'autoLoad');
        spl_autoload_register($callback);
    }

    public function autoLoad($className) {
        var_dump($className);

        $map = array(
            'Tiri\Widget\\' => TIRI_ROOT . '/Widget/',
            'Tiri\Func\\' => TIRI_ROOT . '/Func/',
            'Tiri\\' => TIRI_ROOT . '/Tiri/',
        );
        $fileName = $className.'.php';
        foreach ($map as $ns => $path) {
            if (strpos($className, $ns) === 0) {
                $fileName = str_replace($ns, $path,$className).'.php';
                break;
            }
        }
        // 如果还有进一步的子命名空间，要把空间分隔符，转换成文件目录
        $fileName = str_replace('\\', '/', $fileName);
        // 为兼容用户空间，类名中的下划线要要转换成文件路劲分隔符
        $fileName = str_replace('_', '/', $fileName);

        require $fileName;
    }
}