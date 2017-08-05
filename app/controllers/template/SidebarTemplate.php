<?php
/**
 * SidebarTemplate.php
 */

namespace SoftnCMS\controllers\template;

use SoftnCMS\controllers\Template;
use SoftnCMS\models\managers\SidebarsManager;
use SoftnCMS\models\tables\Sidebar;

/**
 * Class SidebarTemplate
 * @author Nicolás Marulanda P.
 */
class SidebarTemplate extends Template {
    
    private $sidebar;
    
    /**
     * SidebarTemplate constructor.
     *
     * @param Sidebar $sidebar
     */
    public function __construct($sidebar = NULL) {
        parent::__construct();
        $this->sidebar = $sidebar;
    }
    
    public function initRelationship() {
    }
    
    public function initSidebar($sidebarId) {
        $sidebarsManager = new SidebarsManager();
        $this->sidebar   = $sidebarsManager->searchById($sidebarId);
        
        if (empty($this->sidebar)) {
            throw new \Exception('La barra lateral no existe.');
        }
    }
    
    /**
     * @return null|Sidebar
     */
    public function getSidebar() {
        return $this->sidebar;
    }
    
    /**
     * @param null|Sidebar $sidebar
     */
    public function setSidebar($sidebar) {
        $this->sidebar = $sidebar;
    }
    
}
