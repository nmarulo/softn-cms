<?php
/**
 * MenusManager.php
 */

namespace SoftnCMS\models\managers;

use SoftnCMS\models\CRUDManagerAbstract;
use SoftnCMS\models\tables\Menu;
use SoftnCMS\util\Arrays;

/**
 * Class MenusManager
 * @author Nicolás Marulanda P.
 */
class MenusManager extends CRUDManagerAbstract {
    
    const TABLE         = 'menus';
    
    const MENU_NAME     = 'menu_name';
    
    const MENU_URL      = 'menu_url';
    
    const MENU_SUB      = 'menu_sub';
    
    const MENU_POSITION = 'menu_position';
    
    const MENU_TITLE    = 'menu_title';
    
    /**
     * @param Menu $object
     */
    protected function addParameterQuery($object) {
        parent::parameterQuery(self::MENU_NAME, $object->getMenuName(), \PDO::PARAM_STR);
        parent::parameterQuery(self::MENU_POSITION, $object->getMenuPosition(), \PDO::PARAM_INT);
        parent::parameterQuery(self::MENU_SUB, $object->getMenuSub(), \PDO::PARAM_INT);
        parent::parameterQuery(self::MENU_TITLE, $object->getMenuTitle(), \PDO::PARAM_STR);
        parent::parameterQuery(self::MENU_URL, $object->getMenuUrl(), \PDO::PARAM_STR);
    }
    
    protected function getTable() {
        return self::TABLE;
    }
    
    protected function buildObjectTable($result) {
        parent::buildObjectTable($result);
        $menu = new Menu();
        $menu->setId(Arrays::get($result, self::ID));
        $menu->setMenuName(Arrays::get($result, self::MENU_NAME));
        $menu->setMenuPosition(Arrays::get($result, self::MENU_POSITION));
        $menu->setMenuSub(Arrays::get($result, self::MENU_SUB));
        $menu->setMenuTitle(Arrays::get($result, self::MENU_TITLE));
        $menu->setMenuUrl(Arrays::get($result, self::MENU_URL));
        
        return $menu;
    }
    
}
