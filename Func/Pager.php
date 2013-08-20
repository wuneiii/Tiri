<?php
    class Func_Pager{

        public static function get($conf = array('total'=>'' , 'per_page' => '' , 'cur_page' =>'','page_parm'=>'page')){
            extract($conf);
            if($total <= $per_page)
                return null;
            $per_page = intval($per_page) == 0 ? C('per_page') : $per_page;
            $cur_page = intval($cur_page);
            $page_parm = empty($page_parm)?'page':$page_parm;

            //这里直接用了GET变量

            $all_pages = ceil($total/$per_page);

            $ret = Func_Html::table_header();
            $ret .= '<tr><td>';
            $ret .="当前第".($cur_page+1)."页面&nbsp;&nbsp;";
            $raw_url = $_SERVER['REQUEST_URI'];

            $start_page = 0;
            $end_page = $all_pages;
            if($all_pages > 10){
                $start_page = $cur_page - 5;
                $end_page = $cur_page + 5;
                if($start_page < 0 ){
                    $end_page = $cur_page + 5 - $start_page;
                    $start_page = 0;
                }
                if($end_page >= $all_pages){
                    $start_page = $cur_page - 5 + $end_pages - $all_pages;
                    $end_page = $all_pages;
                }

            }

            for($i = $start_page ;$i < $end_page;$i++){

                if($i!=0)$ret .='  | ';

                if(isset($_GET[$page_parm])){
                    //preg_replace("/$page_parm=\d+/","/$page_parm=$i/",$url);
                    $url = preg_replace("/$page_parm=\d+/","$page_parm=$i",$raw_url);
                }else{

                    $url = $raw_url."&$page_parm=$i";
                }
                $ret .= '&nbsp<a href="'.$url.'">'.($i+1).'</a>&nbsp; ';


            }
            $ret .="</td></tr>";
            $ret .=Func_Html::table_footer();
            return $ret;
        }

    }
?>
