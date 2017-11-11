<?php
/**
 * MenusManager.php
 */

namespace SoftnCMS\models\managers;

use SoftnCMS\models\tables\Menu;
use SoftnCMS\util\Arrays;
use SoftnCMS\util\database\ManagerAbstract;
use SoftnCMS\util\Messages;

/**
 * Class MenusManager
 * @author Nicolás Marulanda P.
 */
class MenusManager extends ManagerAbstract {
    
    const TABLE               = 'menus';
    
    const MENU_URL            = 'menu_url';
    
    const MENU_SUB            = 'menu_sub';
    
    const MENU_POSITION       = 'menu_position';
    
    const MENU_TITLE          = 'menu_title';
    
    const MENU_TOTAL_CHILDREN = 'menu_total_children';
    
    const MENU_SUB_PARENT     = 0;
    
    private $rowCountDelete;
    
    public function __construct() {
        parent::__construct();
        $this->rowCountDelete = 0;
    }
    
    /**
     * @return int
     */
    public function getRowCountDelete() {
        return $this->rowCountDelete;
    }
    
    /**
     * @param string $limit
     *
     * @return array
     */
    public function searchAllParent($limit = '') {
        return $this->searchByMenuSub(self::MENU_SUB_PARENT, $limit);
    }
    
    /**
     * Método que obtiene todos los hijos directos de un menu padre.
     *
     * @param int    $menuSub Identificador del menu padre.
     * @param string $limit
     *
     * @return array
     */
    public function searchByMenuSub($menuSub, $limit = '') {
        $orderBy = 'ORDER BY ' . self::MENU_SUB . ' ASC';
        
        if ($menuSub == self::MENU_SUB_PARENT) {
            $orderBy = 'ORDER BY ' . self::COLUMN_ID . ' DESC';
        }
        
        $addConditions = [$orderBy];
        
        if (!empty($limit)) {
            $addConditions[] = "LIMIT $limit";
        }
        
        return parent::searchAllByColumn($menuSub, self::MENU_SUB, \PDO::PARAM_INT, $addConditions);
    }
    
    /**
     * @param int $id
     *
     * @return bool
     */
    public function deleteById($id) {
        $this->rowCountDelete = 0;
        $menu                 = $this->searchById($id);
        
        if (empty($menu)) {
            return FALSE;
        }
        
        $menuIdList = [];
        $menuIdList = $this->getAllChildrenId($id, $menuIdList);
        array_walk($menuIdList, function($menuId) {
            parent::addPrepareStatement(self::COLUMN_ID . $menuId, $menuId, \PDO::PARAM_INT, self::COLUMN_ID);
        });
        
        if (!parent::deleteByPrepareStatement('OR')) {
            return FALSE;
        }
        
        /*
         * Guardo el numero de filas afectadas por la consulta "DELETE",
         * porque, se pierde su valor al usar "updateParentsChildren" y "updatePositions".
         */
        $this->rowCountDelete = $this->getRowCount();
        $parentMenuId         = $menu->getMenuSub();
        
        if ($parentMenuId != self::MENU_SUB_PARENT) {
            if (!$this->updateParentsChildren($parentMenuId, -count($menuIdList))) {
                Messages::addDanger(__('Error al actualizar el numero de hijos de los menus.'), TRUE);
            }
            
            if (!$this->updatePositions($parentMenuId)) {
                Messages::addDanger(__('Error al actualizar las posiciones de los menus.'), TRUE);
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
        
        return $this->updateByColumnId($parentMenu);
    }
    
    /**
     * @param Menu $object
     *
     * @return bool
     */
    public function updateByColumnId($object) {
        $currentMenu = $this->searchById($object->getId());
        
        if (empty($currentMenu) || !parent::updateByColumnId($object)) {
            return FALSE;
        }
        
        $parentMenuId = $object->getMenuSub();
        
        if ($currentMenu->getMenuSub() == self::MENU_SUB_PARENT && $parentMenuId != self::MENU_SUB_PARENT) {
            if (!$this->updateParentsChildren($parentMenuId, $object->getMenuTotalChildren() + 1)) {
                Messages::addDanger(__('Error al actualizar el numero de hijos de los menus.'), TRUE);
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
                
                if ($position != $i + 1 && !$this->updateByColumnId($menu)) {
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
        parent::addPrepareStatement($columnMenuSub, self::MENU_SUB_PARENT, \PDO::PARAM_INT);
        $query  = sprintf('SELECT COUNT(*) AS COUNT FROM %1$s WHERE %2$s = :%2$s', parent::getTableWithPrefix(), $columnMenuSub);
        $result = Arrays::findFirst(parent::getDB()
                                          ->select($query));
        
        return empty($result) ? 0 : $result;
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
                Messages::addDanger(__('Error al actualizar el numero de hijos de los menus.'), TRUE);
                
                return FALSE;
            }
            
            $object->setMenuPosition($this->getLastPosition($parentMenuId) + 1);
        }
        
        return parent::create($object);
    }
    
    private function getLastPosition($parentMenuId) {
        $query = 'SELECT %1$s AS POSITION FROM %2$s WHERE %3$s = :%3$s ORDER BY %1$s DESC LIMIT 1';
        $query = sprintf($query, self::MENU_POSITION, parent::getTableWithPrefix(), self::MENU_SUB);
        parent::addPrepareStatement(self::MENU_SUB, $parentMenuId, \PDO::PARAM_INT);
        $result = Arrays::findFirst(parent::search($query));
        
        return empty($result) ? 0 : $result;
    }
    
    /**
     * @param Menu $object
     */
    protected function prepareStatement($object) {
        parent::addPrepareStatement(self::COLUMN_ID, $object->getId(), \PDO::PARAM_INT);
        parent::addPrepareStatement(self::MENU_POSITION, $object->getMenuPosition(), \PDO::PARAM_INT);
        parent::addPrepareStatement(self::MENU_SUB, $object->getMenuSub(), \PDO::PARAM_INT);
        parent::addPrepareStatement(self::MENU_TITLE, $object->getMenuTitle(), \PDO::PARAM_STR);
        parent::addPrepareStatement(self::MENU_URL, $object->getMenuUrl(), \PDO::PARAM_STR);
        parent::addPrepareStatement(self::MENU_TOTAL_CHILDREN, $object->getMenuTotalChildren(), \PDO::PARAM_INT);
    }
    
    protected function getTable() {
        return self::TABLE;
    }
    
    protected function buildObject($result) {
        $menu = new Menu();
        $menu->setId(Arrays::get($result, self::COLUMN_ID));
        $menu->setMenuPosition(Arrays::get($result, self::MENU_POSITION));
        $menu->setMenuSub(Arrays::get($result, self::MENU_SUB));
        $menu->setMenuTitle(Arrays::get($result, self::MENU_TITLE));
        $menu->setMenuUrl(Arrays::get($result, self::MENU_URL));
        $menu->setMenuTotalChildren(Arrays::get($result, self::MENU_TOTAL_CHILDREN));
        
        return $menu;
    }
    
}
