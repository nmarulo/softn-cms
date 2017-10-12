<?php
/**
 * PagesManager.php
 */

namespace SoftnCMS\models\managers;

use SoftnCMS\models\tables\Page;
use SoftnCMS\util\Arrays;
use SoftnCMS\util\database\ManagerAbstract;

/**
 * Class PagesManager
 * @author Nicolás Marulanda P.
 */
class PagesManager extends ManagerAbstract {
    
    const TABLE               = 'pages';
    
    const PAGE_TITLE          = 'page_title';
    
    const PAGE_STATUS         = 'page_status';
    
    const PAGE_DATE           = 'page_date';
    
    const PAGE_CONTENTS       = 'page_contents';
    
    const PAGE_COMMENT_STATUS = 'page_comment_status';
    
    const PAGE_COMMENT_COUNT  = 'page_comment_count';
    
    public function searchByIdAndStatus($id, $status) {
        parent::addPrepareStatement(self::COLUMN_ID, $id, \PDO::PARAM_INT);
        parent::addPrepareStatement(self::PAGE_STATUS, $status, \PDO::PARAM_INT);
        $query = 'SELECT * FROM %1$s WHERE %2$s = :%2$s AND %3$s = :%3$s';
        $query = sprintf($query, $this->getTableWithPrefix(), self::COLUMN_ID, self::PAGE_STATUS);
        
        return Arrays::findFirst(parent::search($query));
    }
    
    protected function buildObject($result) {
        $page = new Page();
        $page->setId(Arrays::get($result, self::COLUMN_ID));
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
    protected function prepareStatement($object) {
        parent::addPrepareStatement(self::COLUMN_ID, $object->getId(), \PDO::PARAM_INT);
        parent::addPrepareStatement(self::PAGE_TITLE, $object->getPageTitle(), \PDO::PARAM_STR);
        parent::addPrepareStatement(self::PAGE_STATUS, $object->getPageStatus(), \PDO::PARAM_STR);
        parent::addPrepareStatement(self::PAGE_DATE, $object->getPageDate(), \PDO::PARAM_STR);
        parent::addPrepareStatement(self::PAGE_CONTENTS, $object->getPageContents(), \PDO::PARAM_STR);
        parent::addPrepareStatement(self::PAGE_COMMENT_STATUS, $object->getPageCommentStatus(), \PDO::PARAM_STR);
        parent::addPrepareStatement(self::PAGE_COMMENT_COUNT, $object->getPageCommentCount(), \PDO::PARAM_STR);
    }
    
    protected function getTable() {
        return self::TABLE;
    }
    
}
