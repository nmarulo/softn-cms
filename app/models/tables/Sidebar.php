<?php
/**
 * Sidebar.php
 */

namespace SoftnCMS\models\tables;

use SoftnCMS\util\database\TableAbstract;

/**
 * Class Sidebar
 * @author Nicolás Marulanda P.
 */
class Sidebar extends TableAbstract {
    
    /** @var string */
    private $sidebarTitle;
    
    /** @var string */
    private $sidebarContents;
    
    /** @var int */
    private $sidebarPosition;
    
    /**
     * @return string
     */
    public function getSidebarTitle() {
        return $this->sidebarTitle;
    }
    
    /**
     * @param string $sidebarTitle
     */
    public function setSidebarTitle($sidebarTitle) {
        $this->sidebarTitle = $sidebarTitle;
    }
    
    /**
     * @return string
     */
    public function getSidebarContents() {
        return $this->sidebarContents;
    }
    
    /**
     * @param string $sidebarContents
     */
    public function setSidebarContents($sidebarContents) {
        $this->sidebarContents = $sidebarContents;
    }
    
    /**
     * @return int
     */
    public function getSidebarPosition() {
        return $this->sidebarPosition;
    }
    
    /**
     * @param int $sidebarPosition
     */
    public function setSidebarPosition($sidebarPosition) {
        $this->sidebarPosition = $sidebarPosition;
    }
    
}
