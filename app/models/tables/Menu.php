<?php
/**
 * Menu.php
 */

namespace SoftnCMS\models\tables;

use SoftnCMS\models\TableAbstract;

/**
 * Class Menu
 * @author Nicolás Marulanda P.
 */
class Menu extends TableAbstract {
    
    /** @var string */
    private $menuName;
    
    /** @var string */
    private $menuUrl;
    
    /** @var int */
    private $menuSub;
    
    /** @var int */
    private $menuPosition;
    
    /** @var string */
    private $menuTitle;
    
    /**
     * @return string
     */
    public function getMenuName() {
        return $this->menuName;
    }
    
    /**
     * @param string $menuName
     */
    public function setMenuName($menuName) {
        $this->menuName = $menuName;
    }
    
    /**
     * @return string
     */
    public function getMenuUrl() {
        return $this->menuUrl;
    }
    
    /**
     * @param string $menuUrl
     */
    public function setMenuUrl($menuUrl) {
        $this->menuUrl = $menuUrl;
    }
    
    /**
     * @return int
     */
    public function getMenuSub() {
        return $this->menuSub;
    }
    
    /**
     * @param int $menuSub
     */
    public function setMenuSub($menuSub) {
        $this->menuSub = $menuSub;
    }
    
    /**
     * @return int
     */
    public function getMenuPosition() {
        return $this->menuPosition;
    }
    
    /**
     * @param int $menuPosition
     */
    public function setMenuPosition($menuPosition) {
        $this->menuPosition = $menuPosition;
    }
    
    /**
     * @return string
     */
    public function getMenuTitle() {
        return $this->menuTitle;
    }
    
    /**
     * @param string $menuTitle
     */
    public function setMenuTitle($menuTitle) {
        $this->menuTitle = $menuTitle;
    }
    
}
