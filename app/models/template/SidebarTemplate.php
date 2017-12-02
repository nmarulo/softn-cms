<?php
/**
 * SidebarTemplate.php
 */

namespace SoftnCMS\models\template;

use SoftnCMS\models\managers\SidebarsManager;
use SoftnCMS\models\tables\Sidebar;
use SoftnCMS\models\TemplateAbstract;
use SoftnCMS\util\database\DBInterface;
use SoftnCMS\util\Logger;

/**
 * Class SidebarTemplate
 * @author Nicolás Marulanda P.
 */
class SidebarTemplate extends TemplateAbstract {
    
    private $sidebar;
    
    /**
     * SidebarTemplate constructor.
     *
     * @param Sidebar     $sidebar
     * @param bool        $initRelationShip
     * @param string      $siteUrl
     * @param DBInterface $connectionDB
     */
    public function __construct($sidebar = NULL, $initRelationShip = FALSE, $siteUrl = '', DBInterface $connectionDB = NULL) {
        parent::__construct($siteUrl, $connectionDB);
        $this->sidebar = $sidebar;
    }
    
    public function initRelationship() {
    }
    
    public function initSidebar($sidebarId) {
        $sidebarsManager = new SidebarsManager($this->getConnectionDB());
        $this->sidebar   = $sidebarsManager->searchById($sidebarId);
        
        if (empty($this->sidebar)) {
            Logger::getInstance()
                  ->error('La barra lateral no existe.', ['currentSidebarId' => $sidebarId]);
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
