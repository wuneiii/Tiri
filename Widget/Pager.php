<?php
    /** 分页必须从1开始    */
    class Widget_Pager extends Tiri_Widget{

        public static $_pagerParam = 'page';


        /** 外部逻辑取数据的时候，要知道当前在多少页，以便确定limit 的参数  */
        /** 分页参数在url中的名字，不想暴露给外边，就是 $_pagerParam 这个参数   */
        /** 不暴露还有一个方法，就是pager负责获取当前页的数据set，但这就要求内部传递取数据集的条件进来*/
        /** 故要提供这个接口    */
        public static function getCurrentPage(){
            

            $curPage = Tiri_Request::getInstance()->param(self::$_pagerParam);
            /** 未分页，或者第一页   */
            return $curPage == '' ? 1 : $curPage;

        }
        public static function show($total , $perPage){
            
            $total = intval($total);
            if($total == 0){
                Tiri_Error::add('分页总量为0,不符合预期' , __FILE__ , __LINE__);
                return ;
            }

            $_pagerParam = self::$_pagerParam;
            /** 最多显示6页连接    */
            $_showPages = 6;

            if($total == '' || $total <= $perPage){
                return null;
            }
            $cur_page = self::getCurrentPage();

            /** 总页数 */
            
            $totalPageNum  =  ceil ( $total / $perPage );

            $start_page = 1;
            $end_page = $totalPageNum;
            /** 共显示 $_showPages 页，根据当前页，选择开始页 */
            if($totalPageNum > $_showPages){
                $start_page = $cur_page - $_showPages/2;
                $end_page = $cur_page + $_showPages/2;
                /** 开始几页    */
                if($start_page <= 1 ){
                    $_isHead = true;
                    $end_page = $cur_page + $_showPages/2 - $start_page;
                    $start_page = 1;
                }
                /** 结束页 */
                if($end_page >= $totalPageNum){
                    $_isTail = true;
                    $start_page = $cur_page - $_showPages/2  - ( $end_page - $totalPageNum);
                    $end_page = $totalPageNum;
                }
                if($start_page >  1 &&  $end_page < $totalPageNum){
                    $_isMiddle = true;
                }
            }
            /** 显示  */
            
            for($i = $start_page ;$i <= $end_page;$i++){
                if($i == $cur_page){
                    $ret .='<div class="pager_current">'.$i.'</div>';

                }else{
                    /** 当前url 加上pager参数 */
                    $url = Tiri_Request::getInstance()->getUrlWithQuery($_pagerParam ,$i);
                    $ret .='<div class="pager"><a href="'.$url.'">'.$i.'</a></div>';
                }
            }
            if($_isHead){
                $url = Tiri_Request::getInstance()->getUrlWithQuery($_pagerParam , $totalPageNum);
                $ret .='<div>...</div><div class="pager_tail"><a href="'.$url.'">'.$totalPageNum.'</a></div>';
            }
            if($_isTail){
                $url = Tiri_Request::getInstance()->getUrlWithQuery($_pagerParam , 1);
                $ret ='<div class="pager_tail"><a href="'.$url.'">1</a></div><div>...</div>' . $ret;
            }
            if($_isMiddle){

                $url = Tiri_Request::getInstance()->getUrlWithQuery($_pagerParam , 1);
                $ret ='<div class="pager_tail"><a href="'.$url.'">1</a></div><div>...</div>' . $ret;
                $url = Tiri_Request::getInstance()->getUrlWithQuery($_pagerParam , $totalPageNum);
                $ret .='<div>...</div><div class="pager_tail"><a href="'.$url.'">'.$totalPageNum.'</a></div>';

            }

            $ret = '<div class="pager_container">' .$ret. '</div>';
            return $ret;
        }

    }
?>
