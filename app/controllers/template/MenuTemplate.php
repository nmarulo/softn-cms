<?php
/**
 * MenuTemplate.php
 */

namespace SoftnCMS\controllers\template;

use SoftnCMS\controllers\Template;
use SoftnCMS\models\managers\MenusManager;
use SoftnCMS\models\tables\Menu;
use SoftnCMS\util\Logger;

/**
 * Class MenuTemplate
 * @author Nicolás Marulanda P.
 */
class MenuTemplate extends Template {
    
    /** @var array */
    private $subMenuList;
    
    /** @var Menu */
    private $menu;
    
    /**
     * MenuTemplate constructor.
     *
     * @param Menu $menu
     * @param bool $initRelationShip
     */
    public function __construct(Menu $menu = NULL, $initRelationShip = FALSE) {
        parent::__construct();
        $this->menu        = $menu;
        $this->subMenuList = [];
        
        if ($initRelationShip) {
            $this->initRelationship();
        }
    }
    
    public function initRelationship() {
        $this->initSubMenuList();
    }
    
    public function initSubMenuList() {
        if (!empty($this->menu)) {
            $menusManager = new MenusManager();
            $menuList     = $menusManager->searchByMenuSub($this->menu->getId());
            
            $this->subMenuList = array_map(function(Menu $menu) {
                return new MenuTemplate($menu, TRUE);
            }, $menuList);
        }
    }
    
    public function initMenu($menuId) {
        $menusManager = new MenusManager();
        $this->menu   = $menusManager->searchById($menuId);
        
        if (empty($this->menu)) {
            Logger::getInstance()
                  ->error('El menu no existe.', ['currentMenuId' => $menuId]);
            throw new \Exception("El menu no existe.");
        }
    }
    
    /**
     * @return array
     */
    public function getSubMenuList() {
        return $this->subMenuList;
    }
    
    /**
     * @return Menu
     */
    public function getMenu() {
        return $this->menu;
    }
    
    /**
     * @param Menu $menu
     */
    public function setMenu($menu) {
        $this->menu = $menu;
    }
    
}
