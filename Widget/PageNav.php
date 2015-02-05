<?php
namespace Tiri\Widget;
class PageNav {
    private $perPage;
    private $curPage;
    private $total;
    // 显示页码连接的个数
    private $pageBoxNum;
    private $pageParamName;

    public function __construct($total, $perPage) {
        $this->setTotal($total);
        $this->setPerPage($perPage);
        $this->pageParamName = 'page';
        $this->pageBoxNum = 6;
    }

    public function setPerPage($n) {
        $n = intval($n);
        if (!$n) {
            $n = Conf('app.perPage');
        }
        $this->perPage = $n;
    }

    public function setTotal($n) {
        $this->total = intval($n);
    }

    public function setPageBoxNum($n) {
        $this->pageBoxNum = intval($n);
    }

    /**
     * 当前页数，不能设置，只能获取
     * 最小是1
     */
    public function getCurrentPage() {
        $req = Tiri_Request::getInstance();
        $page = $req->getInt($this->pageParamName);
        if ($page <= 0) {
            $page = 1;
        }
        return $page;
    }

    public function getLimit() {
        $curPage = $this->getCurrentPage();
        $start = ($curPage - 1) * $this->perPage;
        return sprintf(' LIMIT %d,%d ', $start, $this->perPage);
    }

    public function getPageNav() {
        if ($this->total <= $this->perPage) {
            return;
        }
        /**
         * 第一步：准备各种变量
         *
         * @var Widget_PageNav
         */
        $this->curPage = $this->getCurrentPage();
        $totalPageNum = ceil($this->total / $this->perPage);

        $request = Tiri_Request::getInstance();
        $startPage = 1;
        $endPage = $totalPageNum;
        $halfPageBoxNum = floor($this->pageBoxNum / 2);
        $pagerHtml = $_isHead = $_isTail = $_isMiddle = null;

        /**
         * 第二步：判断起始页
         * 共显示 $_showPages 页，根据当前页，选择开始页
         *
         */
        if ($totalPageNum > $this->pageBoxNum) {
            $startPage = $this->curPage - $halfPageBoxNum;
            $endPage = $this->curPage + $halfPageBoxNum;
            /** 开始几页    */
            if ($startPage <= 1) {
                $_isHead = true;
                $endPage = $this->curPage + $halfPageBoxNum - $startPage;
                $startPage = 1;
            }
            /** 结束页 */
            if ($endPage >= $totalPageNum) {
                $_isTail = true;
                $startPage = $this->curPage + $halfPageBoxNum - $endPage;
                $endPage = $totalPageNum;
            }
            if ($startPage > 1 && $endPage < $totalPageNum) {
                $_isMiddle = true;
            }
        }
        /**
         * 第三步：
         * 开始拼装html;
         */
        for ($i = $startPage; $i <= $endPage; $i++) {
            if ($i == $this->curPage) {
                $pagerHtml .=
                    '<div class="pager_current pager">' . "\n" .
                    '<a href="javascript:void();">' . $i . '</a>' . "\n" .
                    '</div>' . "\n";

            } else {
                /** 当前url 加上pager参数 */
                $url = $request->getUrlWithQuery($this->pageParamName, $i);
                $pagerHtml .=
                    '<div class="pager">' . "\n" .
                    '<a href="' . $url . '">' . $i . '</a>' . "\n" .
                    '</div>' . "\n";
            }
        }
        if ($_isHead) {
            $url = $request->getUrlWithQuery($this->pageParamName, $totalPageNum);
            $pagerHtml .=
                '<div>...</div>' . "\n"
                . '<div class="pager_tail pager">' . "\n"
                . '<a href="' . $url . '">' . $totalPageNum . '</a>' . "\n"
                . '</div>' . "\n";
        }
        if ($_isTail) {
            $url = $request->getUrlWithQuery($this->pageParamName, 1);
            $pagerHtml = '<div class="pager_tail pager">' . "\n"
                . '<a href="' . $url . '">1</a>' . "\n"
                . '</div>' . "\n"
                . '<div>...</div>' . "\n"
                . $pagerHtml;
        }
        if ($_isMiddle) {
            $url = $request->getUrlWithQuery($this->pageParamName, 1);
            $pagerHtml = '<div class="pager_tail pager">' . "\n"
                . '<a href="' . $url . '">1</a>' . "\n"
                . '</div>' . "\n"
                . '<div>...</div>' . "\n"
                . $pagerHtml . "\n";
            $url = $request->getUrlWithQuery($this->pageParamName, $totalPageNum);
            $pagerHtml .= '<div>...</div>' . "\n"
                . '<div class="pager_tail pager">' . "\n"
                . '<a href="' . $url . '">' . $totalPageNum . '</a>' . "\n"
                . '</div>' . "\n";

        }

        $pagerHtml = '<div class="pager_container">' . "\n"
            . $pagerHtml . "\n"
            . '</div>';
        return $pagerHtml;
    }
}