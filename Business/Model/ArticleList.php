<?php
    /**
     * Autogen @ 2013-22-01 15:49:58
     * mapping to 'article' table of db 
     */
    class Business_Model_Article extends Tiri_Model{
        public function __construct(){
            $this -> _table = 'article';
            $this -> _primary_key = 'id';
            $this -> _fields= array(
                'id',
                'title',    //标题
                'author',   //作者
                'source',   //来源
                'publish_time',//发布时间
                'content',  //内容
                'is_del',   // 是否删除
                'is_top',   // 是否置顶
                'title_corlor', //标题显示颜色
                'cagetory_id',  //所属分类id
                'intro',        //导语
                'keyword',      //meta关键字
                'tag',          //半角逗号分隔的 tag
                'is_hot',       // 是否 热门
                'is_rec',       // 是否推荐
                'is_italic',    // 是否标题斜体显示
                'is_bold',      // 是否标题粗体显示
            );   
        }
    }
?>