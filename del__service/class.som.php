<?php

    $app = TIRI_APP::getInstance();
    $app -> loadFun('pager');
    $app -> loadService('orm');
    $app -> loadService('html');

    define('HTML_TYPE_INPUT',1);
    define('HTML_TYPE_SELECT',2);
    define('HTML_TYPE_TEXTAREA',3);
    define('HTML_TYPE_RADIO',4);
    define('HTML_TYPE_HIDDEN',6);//html的hidden域
    define('HTML_TYPE_JQ_DATE_PICKER',7);//使用jq的datepiepicker控件，2012-04-05 日期格式
    define('HTML_TYPE_UPDATE_TIME',8);//以时间戳形式记录，本条记录最后一次更新时间，每次update时，都会这个时间
    define('HTML_TYPE_TIMESTAMP',9);//时间戳，这种类型，在list页面，会被会用date显示，但不会像上边这个这样自动更新

    define('FORM_ACTION_ADD',1);
    define('FORM_ACTION_DELETE',2);
    define('FORM_ACTION_EDIT',3);
    define('FORM_ACTION_LIST',4);

    define('FORM_STRING_BLACK_ROW','我是空白^_^');
    define('FORM_STRING_EDIT_BUTTON' ,'修改');
    define('FORM_STRING_DELETE_BUTTON','删除');
    define('FORM_STRING_UD_FIELD','操作');
    /**
    * 
    $this->attrs = array(
    'title' =>array(
    'label'=>'标题',
    'type'=>HTML_TYPE_INPUT,
    'source_type'=>DATA_SOURCE_ORM),
    'source'=>array();
    'funcion'=>function name
    );

    * label "文字标签"
    * type html类型，在html类处理的时候，不同的类型会分发给不同的逻辑处理
    * source_type 如果是select 型html标签，则定义select的下来菜单数据来源,分为orm来源和array来源
    * source 如果type未menu，则为array来源，输入array(k=v)即可。
    *        如果为orm型来源，输入array('orm','k fieldname','v fieldname')
    * hidden = true 则这个字段，list中不显示，update和edit均显示，示例http://blog.sina.com.cn/s/blog_608475eb0100h3h1.html
    * vrules = {k:v,k:v}    jquery validate 插件可以识别的验证规则 ，下同 示例http://blog.sina.com.cn/s/blog_608475eb0100h3h1.html
    * vmessages = {k:v,k:v}
    * readonly = 只在list显示，add和update不显示，如果是readonly= true,就应该使用 default_value  doadd的时候使用默认值
    */

    /**
    * @name Standard Orm Method
    * @short SOM
    */
    class SOM{

        private $orm_obj;
        private $query;
        /**
        * 初始化：告知som对象的名字
        * 
        * @param mixed $orm_name  被展现的orm类名称
        * @param mixed $som_action   add,list,update,del 这个参数som内部处理，用户不需要知道，也不能修改
        * @return SOM
        */
        public function SOM($orm_name){
            $this->orm_obj = new $orm_name;

            $app = TIRI_APP::getInstance();
            $this->query = $app->getQuery();

        }
        /**
        * 根据som_action的不同，处理不同的动作，add|update|del|list 
        * 
        * @param mixed $contAction
        * @param mixed $arrArgv
        */
        public  function event_handle($contAction = '',$arrArgv = array()){

            if(empty($contAction)){
                $contAction = $this->query->get['som_action'];
            }
            if(empty($contAction)){
                $contAction = FORM_ACTION_LIST;
            }
            switch($contAction){
                case FORM_ACTION_EDIT:
                    $this->draw_form_edit($arrArgv);
                    break;
                case FORM_ACTION_ADD:
                    $this->draw_form_add($arrArgv);
                    break;
                case FORM_ACTION_DELETE:
                    $this->draw_form_delete($arrArgv);
                    break;
                case FROM_ACTION_LIST:
                default :
                    $this->draw_form_list($arrArgv);
                    break;
            }
        }

        /**
        * @desc som_action  =add
        * 
        * @param mixed $orm_obj  orm对象
        * @param mixed $post_bak_url  增加成功后的跳转返回url，默认继续添加
        */
        private  function draw_form_add($arrAgrv=''){
            $orm_obj = $this->orm_obj;
            if($this->query->post['post_bak'] == 1){
                /**
                * update动作入库前，在根据som配置处理各字段
                * 
                * @var mixed
                */
                foreach($orm_obj->fields as $field_name => $v){
                    $field_attrs = $orm_obj->attrs[$field_name];
                    //1.处理HTML_TYPE_UPDATE_TIME 标签，自动记录时间戳
                    if($field_attrs['type']==HTML_TYPE_UPDATE_TIME){
                        $this->query->post[$field_name] = time();
                    }
                    /**
                    * readonly的add时使用默认值
                    */
                    if($field_attrs['readonly'] === true){
                        $this->query->post[$field_name] =$field_attrs['default_value'];
                    }
                }
                /**
                * 持久化
                * 
                * @var mixed
                */
                $orm_obj ->fill_from_array($this->query->post);
                $orm_obj ->save_to_db();
                alert('提交成功');

                if($arrArgv['post_bak_url']==''){

                    $url = U(
                    $this->query->controler,
                    $this->query->action,
                    array_merge(
                    $this->query->get,
                    array('som_action'=>FORM_ACTION_LIST)
                    )
                    );

                    R($url);
                    exit;
                }
            }

            $html .= html::table_header();
            foreach($orm_obj->fields as $k => $v){
                $v = $orm_obj->attrs[$k];
                //配置了readoly 的不画add表格
                if($v['readonly'] === true){
                    continue;
                }
                if($k != $orm_obj->primary_key){
                    $v['name'] = $k;//这一句很重要
                    $html .='<tr><td>'.$v['label'].'</td><td>'.$this->draw_html_tag($v).'</td></tr>';
                }
                if($v['vrules'] !=''){
                    $_form_validate['rules'] .= $k.':'.$v['vrules'].',';
                }               
                if($v['vmessages'] !=''){
                    $_form_validate['messages'] .= $k.':'.$v['vmessages'].',';
                }
            }
            $html .= $this->draw_html_tag(array('type'=>HTML_TYPE_HIDDEN,'name'=>'post_bak','value'=>'1'));
            $html .='<tr><td>&nbsp;</td><td>'.html::html_submit('提交').'</td></tr>';
            $html .= html::table_footer();


            $form  = html::form_header('','POST',$_form_validate);
            $form .= $html;
            $form .= html::form_footer();
            echo $form;
        }
        /**
        * som_action = list
        * 
        */
        private  function draw_form_list($arrArgvs = ''){

            //处理分页
            $cur_page = $this->query->get['page'];
            $per_page = C('DEFAULT_PER_PAGE');
            $start = $cur_page * $per_page;

            $orm_obj = $this->orm_obj;

            $rows = $orm_obj -> fetch_all($start,$per_page);

            //print_r($cars);exit;
            $page_nav_bar = build_nav_bar($orm_obj->get_total_nums(),$per_page ,$cur_page,'page');

            $html = html::table_header($orm_obj);
            $html .= html::table_row_fields($orm_obj,true);
            if(count($rows) >0 ){
                foreach($rows as $row){
                    $html .= html::table_row($row , $orm_obj , true);
                }
            }else{
                $html .= html::table_black_row($orm_obj);
            }
            $html .= html::table_footer();
            $html .=$page_nav_bar;
            echo $html;            
        }
        /**
        * som_action = update
        * 
        * @param mixed $argv
        */
        private function draw_form_edit($arrArgvs= ''){

            $orm_obj = $this->orm_obj;
            //$orm_obj = new orm_people();

            //1.POST_BAK 
            if($this->query->post['post_bak'] == 1){
                /**
                * update动作入库前，在根据som配置处理各字段
                * 
                * @var mixed
                */

                foreach($orm_obj->fields as $field_name => $v){
                    $field_attrs = $orm_obj->attrs[$field_name];
                    //1.处理HTML_TYPE_UPDATE_TIME 标签，自动记录时间戳
                    if($field_attrs['type']==HTML_TYPE_UPDATE_TIME){
                        $orm_obj -> $field_name = time();
                    }
                }
                /**
                * 持久化
                * 
                * @var mixed
                */
                $orm_obj ->fill_from_array($this->query->post);
                $orm_obj ->save_to_db();
                alert('提交成功');
                if($arrArgvs['post_bak_url']==''){
                    $url = U(
                    $this->query->controler,
                    $this->query->action,
                    array_merge(
                    $this->query->get,
                    array('som_action'=>FORM_ACTION_LIST)
                    )
                    );

                    R($url);
                    exit;
                }

            }

            //2.update form
            $pk = $orm_obj->primary_key;
            $orm_obj -> load_from_db_by_unique_key($pk,$this->query->get[$pk]);

            $html .= html::table_header();
            foreach($orm_obj->fields as $k => $value){
                $v = $orm_obj->attrs[$k];
                $v['value'] = $value;

                //主键显示hidden域
                if($k != $orm_obj->primary_key){
                    $v['name'] = $k;//这一句很重要
                    $html .='<tr><td>'.$v['label'].'</td><td>'.$this->draw_html_tag($v).'</td></tr>';
                }else{
                    $html .= $this->draw_html_tag(array('type'=>HTML_TYPE_HIDDEN,'name'=> $pk,'value'=>$this->query->get[$pk]));
                }

                if($v['vrules'] !=''){
                    $_form_validate['rules'] .= $k.':'.$v['vrules'].',';
                }               
                if($v['vmessages'] !=''){
                    $_form_validate['messages'] .= $k.':'.$v['vmessages'].',';
                }

            }
            $html .= $this->draw_html_tag(array('type'=>HTML_TYPE_HIDDEN,'name'=>'post_bak','value'=>'1'));
            $html .='<tr><td>&nbsp;</td><td>'.html::html_submit('提交').'</td></tr>';
            $html .= html::table_footer();

            $form = html::form_header('','POST',$_form_validate);
            $form .= $html;
            $form .= html::form_footer();
            echo $form;
        }
        /**
        * som_action = delete
        * 
        */
        private function draw_form_delete($arrArgvs=''){

            $orm_obj = $this->orm_obj;
            $pk = $orm_obj -> primary_key;
            $orm_obj -> delete_from_db_by_unique_key($pk,$this->query->get[$pk]);
            if($arrArgv['post_bak_url']==''){
                $url = U(
                $this->query->controler,
                $this->query->action,
                array_merge(
                $this->query->get,
                array('som_action'=>FORM_ACTION_LIST)
                )
                );
                $arrArgv['post_bak_url'] = $url;
            }

            R($arrArgv['post_bak_url']);
            exit;

        }
        /**
        * @input $field_name = array(
        *       'name' => $field_name,
        *       'label' =>'db_field_name',
        *       'type'=>CONT_,
        *       'source_type'=>'',
        *       'source'=>''
        * )
        * 
        * @param mixed $fields
        * @desc : 只有edit和add的时候用这个画html
        */
        private  function draw_html_tag($f){
            switch($f['type']){
                case HTML_TYPE_TEXTAREA:
                    return html::html_textarea($f['name'],$f['value']);
                case HTML_TYPE_HIDDEN:
                    return html::html_hidden($f['name'],$f['value']);
                case HTML_TYPE_SELECT:
                    $data = html_data_source::get_data($f['source_type'],$f['source']);
                    return html::html_select($f['name'],$data,$f['value']);

                case HTML_TYPE_INPUT:
                    return html::html_textbox($f['name'],$f['value']);

                case HTML_TYPE_PASSWORD:
                    return html::html_password($f['name']); 
                case HTML_TYPE_JQ_DATE_PICKER:
                    return html::jquery_date_picker($f['name'],$f['value']);
                case HTML_TYPE_UPDATE_TIME:
                    return som::draw_field_update_time($f['name'],$f['value']);
                case HTML_TYPE_TIMESTAMP:
                    return html::jquery_date_picker($f['name'],$f['value']);
                default:
                    die('绘制html控件时,遇到未知标签：'.$f['type']);


            }
        }


        /**
        * type=HTML_TYPE_UPDATE_TIME 
        * 
        * 不需要绘制html，由som类独立处理
        * @param mixed $name
        */
        public static function draw_field_update_time($name,$value){
            if($value != ''){
                return   date('Y-m-d h:i:s',$value);
            }else{
                return '无时间数据';
            }
        }

    }

    function U_get_query(){
        $app = TIRI_APP::getInstance();
        $get = $app->getQuery()->get;
        unset($get['c']);
        unset($get['a']);
        return $get;
    }
    function U_get_module(){
        $app = TIRI_APP::getInstance();
        $get = $app->getQuery()->get;
        return $get['c'];
    }
    function U_get_action(){
        $app = TIRI_APP::getInstance();
        $get = $app->getQuery()->get;
        return $get['a'];
    }



?>