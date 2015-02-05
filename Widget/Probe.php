<?php
/**
 * 性能探针，记录运行过程中的各种耗时。
 *
 * 业务代码中调用  Widget_Probe::here('XXX') 即可记录下当时的运行耗时
 *
 * 在::report 看到全局性能记录数据
 * Class Widget_Probe
 */

namespace Tiri\Widget;

class Probe {
    static private $_data;
    static private $_clock;

    static public function here($where) {
        self::$_data[$where] = microtime(true);
    }

    static public function report() {
        echo '<div><p>[Debug]Tiri-Framework Flow Track:</p><ol>';

        foreach (self::$_data as $where => $when) {
            if (!isset($_last)) {
                $_last = $when;

            }
            $_timeDetal = sprintf('%.5f', $when - $_last);

            echo '<li>[' . $_timeDetal . 's]' . $where . '</li>';
        }
        echo '</ol><div>';
    }


    static public function startTimer() {
        self::$_clock = microtime(true);
        self::here('start');
    }

    static public function getNowTimer() {
        return microtime(true) - self::$_clock;
    }
}