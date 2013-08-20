<?php
    class Business_ArticleList extends Tiri_Business{

        private $_cagegoryId;

        public function __construct($categoryId){
            $this -> _cagegoryId = $categoryId;    
        }

        public function addNewArticle(){

        }

        /** 编译一篇文章  */
        public function editArticle($id){

        }
        /** 编辑写操作   */
        public function doEidtArticle($_POST){

        }
        public function getTotalNum(){

        }

        /** 列表页显示用  */
        public function getArticleList($pageNo){

        }

        public function getAriticelPager($totalPage , $perPage , $curPage){

        }

        /** 内容页,拿到一篇文章的内容 */
        public function getArticle($id){

        }

    }
?>