<?php
namespace Tiri\Widget;
class Coding{
    static $bitmap = array(
        5,  2,  4,  1,
        29,  3, 28,  0,
        13,  7, 11,  6,
        8, 12,  9, 10,

        15, 14, 23, 16,
        21, 25, 24, 18,
        20, 17, 27, 22,
        26, 19, 30, 31,
    );


    /**
     *
     * intEncode 和intDecode 可以将小于2的32次方的正整数，非线性映射到同范围整数空间
     * 常用于数据库自增id在web展现时用到。
     *
     * 1.需小于 2的32次方
     * 2.必须是非负；
     *
     * @param $id
     * @param array $bitmap
     * @return int
     */
    public static function intEncode($id, array $bitmap=array()) {

        if (empty($bitmap)) {
            $bitmap = self::$bitmap;
        }
        $r = 0;
        $sign = 0x1 << 30;
        $id |= $sign;
        for ($x = 0; $x < 31; $x++) {
            $v = ($id >> $x) & 0x1;
            $r |= ($v << $bitmap[$x]);
        }
        return $r;
    }

    /**
     * 上函数的解码函数
     * @param $id
     * @param array $bitmap
     * @return int
     */
    public static function intDecode($id, array $bitmap=array()) {
        if (empty($bitmap)) {
            $bitmap = self::$bitmap;
        }

        $r = 0;
        for ($x = 0; $x < 30; $x++) {
            $v = ($id >> $bitmap[$x]) & 0x1;
            $r |= ($v << $x);
        }
        return $r;
    }


    /**
     * 字符串加密编码函数，如不指定 $factor 参数。则每次目标码均不同。
     * @param $str
     * @param int $factor
     * @return string|void
     */
    static function  tiriEncode($str , $factor = 0){
        $len = strlen($str);
        if(!$len){
            return;
        }
        if($factor  === 0){
            $factor = mt_rand(1, min(255 , ceil($len / 3)));
        }
        $c = $factor % 8;

        $slice = str_split($str ,$factor);
        for($i=0;$i < count($slice);$i++){
            for($j=0;$j< strlen($slice[$i]) ;$j ++){
                $slice[$i][$j] = chr(ord($slice[$i][$j]) + $c + $i);
            }
        }
        $ret = pack('C' , $factor).implode('' , $slice);
        return self::base64URLEncode($ret);
    }

    /**
     * 和上边函数配对使用
     * @param $str
     * @return bool|string|void
     */
    static function tiriDecode($str){  
        if($str == ''){
            return;
        }     
        $str = self::base64URLDecode($str);
        $factor =  ord(substr($str , 0 ,1));
        $c = $factor % 8;
        $entity = substr($str , 1);
        $slice = str_split($entity , $factor);
        if(!$slice){
            return false;
        }
        for($i=0;$i < count($slice); $i++){
            for($j =0 ; $j < strlen($slice[$i]); $j++){
                $slice[$i][$j] = chr(ord($slice[$i][$j]) - $c - $i );
            }
        }
        return implode($slice);
    }

    /**
     * base64编码，替换掉浏览器中会造成歧义的字符
     * @param $data
     * @return string
     */
    static function base64URLEncode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    static function base64URLDecode($data) {
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }

    /**
     * 字符串异或操作
     * @param $str
     * @return mixed
     */
    static function stringXor($str){
        for ($i = 0; $i < strlen($str); ++$i) {
            $str[$i] = chr(ord($str[$i]) ^ 0x7F);
        }
        return $str;
    }
}
