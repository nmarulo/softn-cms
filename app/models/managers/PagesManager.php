<?php
/**
 * PagesManager.php
 */

namespace SoftnCMS\models\managers;

use SoftnCMS\models\CRUDManagerAbstract;
use SoftnCMS\models\tables\Page;
use SoftnCMS\util\Arrays;

/**
 * Class PagesManager
 * @author Nicolás Marulanda P.
 */
class PagesManager extends CRUDManagerAbstract {
    
    const TABLE               = 'pages';
    
    const PAGE_TITLE          = 'page_title';
    
    const PAGE_STATUS         = 'page_status';
    
    const PAGE_DATE           = 'page_date';
    
    const PAGE_CONTENTS       = 'page_contents';
    
    const PAGE_COMMENT_STATUS = 'page_comment_status';
    
    const PAGE_COMMENT_COUNT  = 'page_comment_count';
    
    protected function buildObjectTable($result) {
        parent::buildObjectTable($result);
        
        $page = new Page();
        $page->setId(Arrays::get($result, self::ID));
        $page->setPageTitle(Arrays::get($result, self::PAGE_TITLE));
        $page->setPageStatus(Arrays::get($result, self::PAGE_STATUS));
        $page->setPageDate(Arrays::get($result, self::PAGE_DATE));
        $page->setPageContents(Arrays::get($result, self::PAGE_CONTENTS));
        $page->setPageCommentStatus(Arrays::get($result, self::PAGE_COMMENT_STATUS));
        $page->setPageCommentCount(Arrays::get($result, self::PAGE_COMMENT_COUNT));
        
        return $page;
    }
    
    /**
     * @param Page $object
     */
    protected function addParameterQuery($object) {
        parent::parameterQuery(self::PAGE_TITLE, $object->getPageTitle(), \PDO::PARAM_STR);
        parent::parameterQuery(self::PAGE_STATUS, $object->getPageStatus(), \PDO::PARAM_STR);
        parent::parameterQuery(self::PAGE_DATE, $object->getPageDate(), \PDO::PARAM_STR);
        parent::parameterQuery(self::PAGE_CONTENTS, $object->getPageContents(), \PDO::PARAM_STR);
        parent::parameterQuery(self::PAGE_COMMENT_STATUS, $object->getPageCommentStatus(), \PDO::PARAM_STR);
        parent::parameterQuery(self::PAGE_COMMENT_COUNT, $object->getPageCommentCount(), \PDO::PARAM_STR);
    }
    
    protected function getTable() {
        return self::TABLE;
    }
    
}
