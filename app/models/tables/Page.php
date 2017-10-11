<?php
/**
 * Page.php
 */

namespace SoftnCMS\models\tables;

use SoftnCMS\util\database\TableAbstract;

/**
 * Class Page
 * @author Nicolás Marulanda P.
 */
class Page extends TableAbstract {
    
    /** @var string */
    private $pageTitle;
    
    /** @var int */
    private $pageStatus;
    
    /** @var string */
    private $pageDate;
    
    /** @var string */
    private $pageContents;
    
    /** @var int */
    private $pageCommentStatus;
    
    /** @var int */
    private $pageCommentCount;
    
    /**
     * @return int
     */
    public function getPageStatus() {
        return $this->pageStatus;
    }
    
    /**
     * @param int $pageStatus
     */
    public function setPageStatus($pageStatus) {
        $this->pageStatus = $pageStatus;
    }
    
    /**
     * @return string
     */
    public function getPageTitle() {
        return $this->pageTitle;
    }
    
    /**
     * @param string $pageTitle
     */
    public function setPageTitle($pageTitle) {
        $this->pageTitle = $pageTitle;
    }
    
    /**
     * @return string
     */
    public function getPageDate() {
        return $this->pageDate;
    }
    
    /**
     * @param string $pageDate
     */
    public function setPageDate($pageDate) {
        $this->pageDate = $pageDate;
    }
    
    /**
     * @return string
     */
    public function getPageContents() {
        return $this->pageContents;
    }
    
    /**
     * @param string $pageContents
     */
    public function setPageContents($pageContents) {
        $this->pageContents = $pageContents;
    }
    
    /**
     * @return int
     */
    public function getPageCommentStatus() {
        return $this->pageCommentStatus;
    }
    
    /**
     * @param int $pageCommentStatus
     */
    public function setPageCommentStatus($pageCommentStatus) {
        $this->pageCommentStatus = $pageCommentStatus;
    }
    
    /**
     * @return int
     */
    public function getPageCommentCount() {
        return $this->pageCommentCount;
    }
    
    /**
     * @param int $pageCommentCount
     */
    public function setPageCommentCount($pageCommentCount) {
        $this->pageCommentCount = $pageCommentCount;
    }
    
}
