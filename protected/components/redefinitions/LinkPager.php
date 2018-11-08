<?php

/**
 * Description of LinkPager
 *
 * @author Renaud
 */
class LinkPager extends CLinkPager {

    // public $maxButtonCount=5;
    public $header = '';

    public function init() {

        $this->cssFile = Yii::app()->request->baseUrl . '/css/pager.css';

        if ($this->nextPageLabel === null)
            $this->nextPageLabel = Yii::t('texts', 'PAGER_NEWER');
        if ($this->prevPageLabel === null)
            $this->prevPageLabel = Yii::t('texts', 'PAGER_OLDER');
        if ($this->firstPageLabel === null)
            $this->firstPageLabel = Yii::t('texts', '&lt;&lt; First');
        if ($this->lastPageLabel === null)
            $this->lastPageLabel = Yii::t('texts', 'Last &gt;&gt;');
        if ($this->header === null)
            $this->header = Yii::t('texts', 'Go to page: ');

        if (!isset($this->htmlOptions['id']))
            $this->htmlOptions['id'] = $this->getId();
        if (!isset($this->htmlOptions['class']))
            $this->htmlOptions['class'] = 'yiiPager';
    }

    /**
     * Creates the page buttons.
     * @return array a list of page buttons (in HTML code).
     */
//    protected function createPageButtons()
//    {
//        if(($pageCount=$this->getPageCount())<=1)
//            return array();
//
//        list($beginPage,$endPage)=$this->getPageRange();
//        $currentPage=$this->getCurrentPage(false); // currentPage is calculated in getPageRange()
//        $buttons=array();
//
//        // first page
//        $buttons[]=$this->createPageButton($this->firstPageLabel,0,self::CSS_FIRST_PAGE,$currentPage<=0,false);
//
//        // prev page
//        if(($page=$currentPage-1)<0)
//            $page=0;
//        $buttons[]=$this->createPageButton($this->prevPageLabel,$page,self::CSS_PREVIOUS_PAGE,$currentPage<=0,false);
//
//        //2 first pages
//        if($currentPage==3)
//        {
//            $buttons[]=$this->createPageButton(1,0,self::CSS_INTERNAL_PAGE,false,0==$currentPage);
//            $buttons[]= ' ... ';
//        }
//
//        if($currentPage>3)
//        {
//            $buttons[]=$this->createPageButton(1,0,self::CSS_INTERNAL_PAGE,false,0==$currentPage);
//            $buttons[]=$this->createPageButton(2,1,self::CSS_INTERNAL_PAGE,false,1==$currentPage);
//            $buttons[]= ' ... ';
//        }
//
//        // internal pages
//        for($i=$beginPage;$i<=$endPage;++$i)
//            $buttons[]=$this->createPageButton($i+1,$i,self::CSS_INTERNAL_PAGE,false,$i==$currentPage);
//
//        //3 lasts pages
//        if($endPage<$pageCount-2)
//        {
//            $buttons[]= ' ... ';
//            for($i=$pageCount-2;$i<=$pageCount-1;++$i)
//            $buttons[]=$this->createPageButton($i+1,$i,self::CSS_INTERNAL_PAGE,false,$i==$currentPage);
//        }
//
//        if($endPage==$pageCount-2)
//        {
//            $buttons[]= ' ... ';
//            $buttons[]=$this->createPageButton($pageCount,$pageCount-1,self::CSS_INTERNAL_PAGE,false,$pageCount-1==$currentPage);
//        }   
//
//        // next page
//        if(($page=$currentPage+1)>=$pageCount-1)
//            $page=$pageCount-1;
//        $buttons[]=$this->createPageButton($this->nextPageLabel,$page,self::CSS_NEXT_PAGE,$currentPage>=$pageCount-1,false);
//
//        // last page
//        $buttons[]=$this->createPageButton($this->lastPageLabel,$pageCount-1,self::CSS_LAST_PAGE,$currentPage>=$pageCount-1,false);
//
//        return $buttons;
//    }
}
