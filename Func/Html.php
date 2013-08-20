<?php

    /**
    * @author： tirisfal.sing@gmail.com
    * @desc ：每个方法负责独立完成一种html控件的各种类型操作的绘制
    * @note ： 尽管这个类充分的SOM类使用，但一定要保持 CLASS html的独立性
    *           不能应为som的存在，而丧失html 类的独立性
    * 
    *           每加进来一个function都要能独立给调用，并完成绘制功能
    *           并且有通用普适的参数接口
    * @date ： 2012年4月30日13:43:11
    * 
    * @version：V2012-4-30  
    *           增加了第一个非常规的空间jq_date_picker
    *           同时在SOM类中增加了update-time控件
    *           在犹豫update_time空间到底是在html类型还是在SOM类中的时候，明确了以上html类的定位。
    *           但看到很多fucntion已经成了som独有的function了，后边引入新function的时候注意下
    * 
    * 
    * 
    */
    class Func_Html {
        public static function table_header($orm_obj = ''){
            return '
            <table width="100%" border="0" cellpadding="0" cellspacing="0" class="tab01">';
        }
        public static function table_row_fields($orm_obj,$with_edit = false){
            if(isset($orm_obj)){
                $ret .='<tr>';
                foreach($orm_obj-> fields as $k =>$v){
                    $field = $orm_obj->attrs[$k];
                    if(!$field['hidden'])
                        $ret .='<th>'.$field['label'].'</th>';
                }
                if($with_edit){
                    $ret .='<th>'.FORM_STRING_UD_FIELD.'</th>';
                }
                $ret .='</tr>';
            }
            return $ret;
        }

        public static function table_footer(){
            return '
            </table>';
        }
        public static function table_black_row($orm_obj){
            $ret .='
            <tr>';
            foreach($orm_obj-> fields as $k =>$v){
                if(!$orm_obj->attrs['hidden'])
                    $ret .='<td>'.FORM_STRING_BLACK_ROW.'</td>';
            }
            $ret .='
            </tr>';
            return $ret;
        }
        public static function table_row($row , $orm_obj, $with_edit = false ){
            $ret = '
            <tr>';
            foreach ($row->fields as $k => $v){
                if(!$row->attrs[$k]['hidden']){
                    if($row->attrs[$k]['type'] == HTML_TYPE_SELECT && $v != ''){
                        $static_v = $k.'_data_source';
                        $data= html_data_source::get_data($row->attrs[$k]['source_type'],$row->attrs[$k]['source']);

                        $v = $data[$v]==''?'&nbsp;':$data[$v];
                    }
                    //这个类型会在显示的时候使用时间戳
                    if($row->attrs[$k]['type'] == HTML_TYPE_TIMESTAMP){
                        $v = date('Y-m-d h:i:s',$data[$v]);
                    }
                    if($row->attrs[$k]['type'] == HTML_TYPE_UPDATE_TIME){
                        if($v != '')
                            $v = date('Y-m-d h:i:s',$v);
                    }
                    $ret .='<td>'.$v.'</td>';
                }
            }
            if($with_edit){
                $app = TIRI_APP::getInstance();
                $query  = $app->getQuery();
                $ret .='<td>
                <a href="'.
                U(
                $query->controler,
                $query->action,
                array_merge(
                $query->get,
                array('som_action'=>FORM_ACTION_EDIT,
                $orm_obj->primary_key => $row->fields[$orm_obj->primary_key]
                )
                )
                ).'">修改<a>|
                <a href="'.
                U(
                $query->controler,
                $query->action,
                array_merge(
                $query->get,
                array('som_action'=>FORM_ACTION_DELETE,
                $orm_obj->primary_key => $row->fields[$orm_obj->primary_key]
                )
                )
                ).'" onclick="return confirm(\'确认删除?\');">删除</a>
                </td>';
            }
            $ret .= '
            </tr>';
            return $ret;
        }
        public static function form_header($action = '',$method = 'post',$form_validate = ''){
            $ret = '
            <script>
            $(function(){
            $("#'.html::gen_form_id_from_action($action).'").validate(
            {
            rules:{'.$form_validate['rules'].'},
            messages:{'.$form_validate['messages'].'}
            }
            );
            });
            </script>

            <form action="'.$action.'" method="'.$method.'" id="'.html::gen_form_id_from_action($action).'">';
            return $ret;
        }
        public  static function form_footer(){

            return '</form>';
        }

        

        public static function start(){
            echo '<html xmlns="http://www.w3.org/1999/xhtml">
            <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>HTML::START</title>
            </head>

            <body>';
        }
        public static function end(){
            echo ' </body></html>';
        }

        public static function css(){

        }
        public static function js(){

        }

        public static function jquery_date_picker($name,$value){
            $ret ='
            <script>
            $(function() {
            $( "#'.$name.'_date_picker" ).datepicker({dateFormat:"yy-mm-dd"});
            });
            </script>
            <input type="input" name="'.$name.'" value="'.$value.'" id="'.$name.'_date_picker">
            ';
            return $ret;

        }

        public static function  gen_form_id_from_action($action){
            return str_replace(array('&','?','&','/','|'),'_',$action)."_form";
        }

    }


    define('DATA_SOURCE_ORM','date-from-orm-field');
    define('DATA_SOURCE_MENU','data-from-given-array-k-to-v');
    class html_data_source{
        /**
        * @desc 自动form部分控件的数据源使用此方法填充数据
        * @desc 重要:此方法硬限制最多30条数据，不要将更多的数据表作为数据源
        * 
        * @param mixed $data_source_type  CONST::DATE_SOURCE_OMR|MENU
        * @param mixed $data_source       orm:: array('orm_obj','key_field_name','value_field_name')
        */
        static public function get_data($data_source_type , $data_source){
            switch($data_source_type){
                case DATA_SOURCE_MENU:
                    return $data_source;
                case DATA_SOURCE_ORM:
                    $orm_obj  = new $data_source[0];
                    $rows = $orm_obj ->fetch_all(0, 30);
                    if(count($rows)>0){
                        foreach($rows as $row)
                            $ret[$row->$data_source[1]] = $row->$data_source[2];
                    }
                    return $ret;

            }
        }
    }  
?>