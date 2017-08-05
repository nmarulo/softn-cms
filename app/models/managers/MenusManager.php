<?php
/**
 * MenusManager.php
 */

namespace SoftnCMS\models\managers;

use SoftnCMS\models\CRUDManagerAbstract;
use SoftnCMS\models\tables\Menu;
use SoftnCMS\util\Arrays;
use SoftnCMS\util\Messages;

/**
 * Class MenusManager
 * @author Nicolás Marulanda P.
 */
class MenusManager extends CRUDManagerAbstract {
    
    const TABLE               = 'menus';
    
    const MENU_URL            = 'menu_url';
    
    const MENU_SUB            = 'menu_sub';
    
    const MENU_POSITION       = 'menu_position';
    
    const MENU_TITLE          = 'menu_title';
    
    const MENU_TOTAL_CHILDREN = 'menu_total_children';
    
    const MENU_SUB_PARENT     = 0;
    
    /**
     * @param array $filters
     *
     * @return array
     */
    public function searchAllParent($filters = []) {
        return $this->searchByMenuSub(self::MENU_SUB_PARENT, $filters);
    }
    
    /**
     * Método que obtiene todos los hijos directos de un menu padre.
     *
     * @param int   $menuSub Identificador del menu padre.
     * @param array $filters
     *
     * @return array
     */
    public function searchByMenuSub($menuSub, $filters = []) {
        $limit         = Arrays::get($filters, 'limit');
        $columnMenuSub = self::MENU_SUB;
        parent::parameterQuery($columnMenuSub, $menuSub, \PDO::PARAM_INT);
        $orderBy = 'ORDER BY ' . self::MENU_SUB . ' ASC ';
        
        if ($menuSub == self::MENU_SUB_PARENT) {
            $orderBy = 'ORDER BY ' . self::ID . ' DESC ';
        }
        
        $query = sprintf('SELECT * FROM %1$s WHERE %2$s = :%2$s %3$s', parent::getTableWithPrefix(), $columnMenuSub, $orderBy);
        
        if (!empty($limit)) {
            $query .= " LIMIT $limit";
        }
        
        return parent::readData($query);
    }
    
    public function delete($id) {
        $menu = $this->searchById($id);
        
        if (empty($menu)) {
            return FALSE;
        }
        
        $menuIdList = [];
        $menuIdList = $this->getAllChildrenId($id, $menuIdList);
        array_walk($menuIdList, function($menuId) {
            $this->parameterQuery(self::ID . $menuId, $menuId, \PDO::PARAM_INT, self::ID);
        });
        
        if (!parent::deleteBy('OR')) {
            return FALSE;
        }
        
        $parentMenuId = $menu->getMenuSub();
        
        if ($parentMenuId != self::MENU_SUB_PARENT) {
            if (!$this->updateParentsChildren($parentMenuId, -count($menuIdList))) {
                Messages::addDanger('Error al actualizar el numero de hijos de los menus.', TRUE);
            }
            
            if (!$this->updatePositions($parentMenuId)) {
                Messages::addDanger('Error al actualizar las posiciones de los menus.', TRUE);
            }
        }
        
        return TRUE;
    }
    
    /**
     * @param int   $menuId
     * @param array $menuIdList
     *
     * @return array
     */
    private function getAllChildrenId($menuId, $menuIdList) {
        $menu = $this->searchById($menuId);
        
        if (empty($menu)) {
            return $menuIdList;
        }
        
        $menuIdList[]  = $menuId;
        $totalChildren = $menu->getMenuTotalChildren();
        
        if ($totalChildren > 0) {
            $menuSub = $this->searchByMenuSub($menuId);
            
            if (empty($menuSub)) {
                return $menuIdList;
            }
            
            array_walk($menuSub, function(Menu $menu) use (&$menuIdList) {
                $menuIdList = $this->getAllChildrenId($menu->getId(), $menuIdList);
            });
        }
        
        return $menuIdList;
    }
    
    /**
     * Método que actualiza el numero de hijos de los menus de forma ascendente.
     *
     * @param int $parentMenuId
     * @param int $num
     *
     * @return bool
     */
    private function updateParentsChildren($parentMenuId, $num) {
        $parentMenu = $this->searchById($parentMenuId);
        
        if (empty($parentMenu)) {
            return FALSE;
        }
        
        $parentMenu->setMenuTotalChildren($parentMenu->getMenuTotalChildren() + $num);
        $parentMenuId = $parentMenu->getMenuSub();
        
        if ($parentMenuId != self::MENU_SUB_PARENT) {
            if (!$this->updateParentsChildren($parentMenuId, $num)) {
                return FALSE;
            }
        }
        
        return $this->update($parentMenu);
    }
    
    /**
     * @param Menu $object
     *
     * @return bool
     */
    public function update($object) {
        $currentMenu = $this->searchById($object->getId());
        
        if (empty($currentMenu) || !parent::update($object)) {
            return FALSE;
        }
        
        $parentMenuId = $object->getMenuSub();
        
        if ($currentMenu->getMenuSub() == self::MENU_SUB_PARENT && $parentMenuId != self::MENU_SUB_PARENT) {
            if (!$this->updateParentsChildren($parentMenuId, $object->getMenuTotalChildren() + 1)) {
                Messages::addDanger('Error al actualizar el numero de hijos de los menus.', TRUE);
            }
        }
        
        return TRUE;
    }
    
    private function updatePositions($parentMenuId) {
        $menusChildren = $this->searchByMenuSub($parentMenuId);
        
        if (is_array($menusChildren) && empty($menusChildren)) {
            return TRUE;
        }
        
        $len      = count($menusChildren);
        $notError = TRUE;
        
        for ($i = 0; $i < $len && $notError; ++$i) {
            $menu = Arrays::get($menusChildren, $i);
            
            if (empty($menu)) {
                $notError = FALSE;
            } else {
                $position = $menu->getMenuPosition();
                $menu->setMenuPosition($i + 1);
                
                if ($position != $i + 1 && !$this->update($menu)) {
                    $notError = FALSE;
                }
            }
        }
        
        return $notError;
    }
    
    /**
     * @param $menuId
     *
     * @return bool|Menu|null
     */
    public function searchParent($menuId) {
        $menu = $this->searchById($menuId);
        
        if (empty($menu)) {
            return NULL;
        }
        
        $parentMenu = $menu->getMenuSub();
        
        if ($parentMenu == self::MENU_SUB_PARENT) {
            return $menu;
        }
        
        return $this->searchParent($parentMenu);
    }
    
    public function count() {
        $columnMenuSub = self::MENU_SUB;
        parent::parameterQuery($columnMenuSub, self::MENU_SUB_PARENT, \PDO::PARAM_INT);
        $query  = sprintf('SELECT COUNT(*) AS COUNT FROM %1$s WHERE %2$s = :%2$s', parent::getTableWithPrefix(), $columnMenuSub);
        $result = parent::select($query);
        $result = Arrays::get($result, 0);
        $result = Arrays::get($result, 'COUNT');
        
        return $result === FALSE ? 0 : $result;
    }
    
    /**
     * @param Menu $object
     *
     * @return bool
     */
    public function create($object) {
        $parentMenuId = $object->getMenuSub();
        $object->setMenuTotalChildren(0);
        $object->setMenuPosition(0);
        
        if ($parentMenuId != self::MENU_SUB_PARENT) {
            if (!$this->updateParentsChildren($parentMenuId, 1)) {
                Messages::addDanger('Error al actualizar el numero de hijos de los menus.', TRUE);
                
                return FALSE;
            }
            
            $object->setMenuPosition($this->getLastPosition($parentMenuId) + 1);
        }
        
        return parent::create($object);
    }
    
    private function getLastPosition($parentMenuId) {
        $query = 'SELECT %1$s AS POSITION FROM %2$s WHERE %3$s = :%3$s ORDER BY %1$s DESC LIMIT 1';
        
        $query = sprintf($query, self::MENU_POSITION, parent::getTableWithPrefix(), self::MENU_SUB);
        parent::parameterQuery(self::MENU_SUB, $parentMenuId, \PDO::PARAM_INT);
        $result = parent::select($query);
        $result = Arrays::get($result, 0);
        $result = Arrays::get($result, 'POSITION');
        
        return $result === FALSE ? 0 : $result;
    }
    
    /**
     * @param Menu $object
     */
    protected function addParameterQuery($object) {
        parent::parameterQuery(self::MENU_POSITION, $object->getMenuPosition(), \PDO::PARAM_INT);
        parent::parameterQuery(self::MENU_SUB, $object->getMenuSub(), \PDO::PARAM_INT);
        parent::parameterQuery(self::MENU_TITLE, $object->getMenuTitle(), \PDO::PARAM_STR);
        parent::parameterQuery(self::MENU_URL, $object->getMenuUrl(), \PDO::PARAM_STR);
        parent::parameterQuery(self::MENU_TOTAL_CHILDREN, $object->getMenuTotalChildren(), \PDO::PARAM_INT);
    }
    
    protected function getTable() {
        return self::TABLE;
    }
    
    protected function buildObjectTable($result) {
        parent::buildObjectTable($result);
        $menu = new Menu();
        $menu->setId(Arrays::get($result, self::ID));
        $menu->setMenuPosition(Arrays::get($result, self::MENU_POSITION));
        $menu->setMenuSub(Arrays::get($result, self::MENU_SUB));
        $menu->setMenuTitle(Arrays::get($result, self::MENU_TITLE));
        $menu->setMenuUrl(Arrays::get($result, self::MENU_URL));
        $menu->setMenuTotalChildren(Arrays::get($result, self::MENU_TOTAL_CHILDREN));
        
        return $menu;
    }
    
}
