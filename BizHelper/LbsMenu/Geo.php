<?php

namespace Sloop\Lbs;

class Geo {

    /** 系统AppInit时调用此方法，配置静态路由，拦截一下2个路由，响应geo的异步数据请求   */
    static function init() {
        Tiri_Router::addFixedRouter('_func_geo', 'get_city_select_list', array(new Func_Geo, 'getCitySelectList'));
        Tiri_Router::addFixedRouter('_func_geo', 'get_area_select_list', array(new Func_Geo, 'getAreaSelectList'));
    }

    /** 响应ajax请求 ,执行完毕即结束 */
    public function getCitySelectList() {
        $provinceId = Tiri_Request::getInstance()->param('pid');
        $styleClass = Tiri_Request::getInstance()->param('class');
        echo self::return_select_city_id($provinceId, '', 'city', $styleClass);
        return Tiri_Router::ROUTER_STOP;
    }

    public function getAreaSelectList() {
        $cityId = Tiri_Request::getInstance()->param('cid');
        $styleClass = Tiri_Request::getInstance()->param('class');
        echo self::return_select_area_id($cityId, '', 'area', $styleClass);
        return Tiri_Router::ROUTER_STOP;

    }

    //获取省一级的地区select下拉框
    //同时输出js，驱动同步更新同页面的城市列表，修改 #city_id的元素
    static function return_select_provience_id($selected = -1, $name = '', $class = '') {
        $orm_p = new Func_Geo_Model_GeoProvince();
        $all_p = $orm_p->getAllMatch();
        $ret = '

            <script>
            function __geo_reload_city(pid){
            var url = "' . U('_func_geo', 'get_city_select_list') . '";
            $.get(url , {"pid":pid,"class":"' . $class . '"} ,function(result,textStatus){

            $("#city_div").html(result);
            });
            }

            </script>
            <select name="' . $name . '" onchange="__geo_reload_city(this.value)" class="' . $class . '">';
        while (!$all_p->isEnd()) {
            $p = $all_p->getNext();
            $ret .= '<option value="' . $p->provinceID . '"';
            if ($p->provinceID == $selected) {
                $ret .= 'selected ="selected"';
            }
            $ret .= '>' . $p->pname . '</option>' . "\n";
        }
        $ret .= '</select>';
        return $ret;
    }

    //获取省一级的地区select下拉框
    static function return_select_city_id($province_id, $selected = -1, $name = '', $class = '') {
        if (empty($province_id)) return null;
        $orm_c = new Func_Geo_Model_GeoCity();
        $orm_c->provinceID = $province_id;

        $all_c = $orm_c->getAllMatch();
        if (!$all_c) return null;
        $ret = '
            <script>
            function __geo_reload_area(cid){
            var url = "' . U('_func_geo', 'get_area_select_list') . '";
            $.get(url , {"cid":cid,"class":"' . $class . '"} ,function(result,textStatus){

            $("#area_div").html(result);
            });
            }

            </script>
            <select name="' . $name . '" onchange="__geo_reload_area(this.value);" class="' . $class . '">
            <option>请选择</option>';
        while (!$all_c->isEnd()) {
            $c = $all_c->getNext();

            $ret .= '<option value="' . $c->cityID . '"';
            if ($c->cityID == $selected) {
                $ret .= 'selected ="selected"';
            }
            $ret .= '>' . $c->city . '</option>' . "\n";
        }
        $ret .= '</select>';
        return $ret;
    }

    //获取省一级的地区select下拉框
    static function return_select_area_id($city_id, $selected = -1, $name = '', $class = '') {
        if (empty($city_id)) return null;
        $orm_a = new Func_Geo_Model_GeoArea();
        $orm_a->cityID = $city_id;
        $all_a = $orm_a->getAllMatch();

        $ret = '
            <select name="' . $name . '" class="' . $class . '"><option>请选择</option>';
        while (!$all_a->isEnd()) {
            $a = $all_a->getNext();

            $ret .= '<option value="' . $a->areaID . '"';
            if ($a->areaID == $selected) {
                $ret .= 'selected ="selected"';
            }
            $ret .= '>' . $a->area . '</option>' . "\n";
        }
        $ret .= '</select>';
        return $ret;
    }


    static function getProvinceNameById($id) {
        $model = new Func_Geo_Model_GeoProvince();
        $model->loadByUniqueKey('provinceID', $id);
        return $model->pname;
    }

    static function getCityNameById($id) {
        if ($id == '') return;
        $model = new Func_Geo_Model_GeoCity();
        $model->loadByUniqueKey('cityID', $id);
        return $model->city;
    }

    static function getAreaNameById($id) {
        $model = new Func_Geo_Model_GeoArea();
        $model->loadByUniqueKey('areaID', $id);
        return $model->area;
    }

}