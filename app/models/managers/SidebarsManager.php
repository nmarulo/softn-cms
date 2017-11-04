<?php
/**
 * SidebarsManager.php
 */

namespace SoftnCMS\models\managers;

use SoftnCMS\models\tables\Sidebar;
use SoftnCMS\util\Arrays;
use SoftnCMS\util\database\ManagerAbstract;
use SoftnCMS\util\Messages;

/**
 * Class SidebarsManager
 * @author Nicolás Marulanda P.
 */
class SidebarsManager extends ManagerAbstract {
    
    const TABLE            = 'sidebars';
    
    const SIDEBAR_TITLE    = 'sidebar_title';
    
    const SIDEBAR_CONTENTS = 'sidebar_contents';
    
    const SIDEBAR_POSITION = 'sidebar_position';
    
    /**
     * @param Sidebar $object
     *
     * @return bool
     */
    public function create($object) {
        $object->setSidebarPosition($this->count() + 1);
        
        return parent::create($object);
    }
    
    public function deleteById($id) {
        if (!parent::deleteById($id)) {
            return FALSE;
        }
        
        if (!$this->updatePositions()) {
            Messages::addDanger(__('Error al actualizar las posiciones de las barras laterales.'));
        }
        
        return TRUE;
    }
    
    private function updatePositions() {
        $sidebars = $this->searchAll();
        
        $len      = count($sidebars);
        $notError = TRUE;
        
        for ($i = 0; $i < $len && $notError; ++$i) {
            $sidebar = Arrays::get($sidebars, $i);
            
            if (empty($sidebar)) {
                $notError = FALSE;
            } else {
                $position = $sidebar->getSidebarPosition();
                $sidebar->setSidebarPosition($i + 1);
                
                if ($position != $i + 1 && !$this->updateByColumnId($sidebar)) {
                    $notError = FALSE;
                }
            }
        }
        
        return $notError;
    }
    
    public function searchAll($limit = '', $orderBy = '') {
        if (empty($orderBy)) {
            $orderBy = self::SIDEBAR_POSITION . ' ASC';
        }
        
        return parent::searchAll($limit, $orderBy);
    }
    
    /**
     * @param Sidebar $object
     */
    protected function prepareStatement($object) {
        parent::addPrepareStatement(self::COLUMN_ID, $object->getId(), \PDO::PARAM_INT);
        parent::addPrepareStatement(self::SIDEBAR_CONTENTS, $object->getSidebarContents(), \PDO::PARAM_STR);
        parent::addPrepareStatement(self::SIDEBAR_POSITION, $object->getSidebarPosition(), \PDO::PARAM_INT);
        parent::addPrepareStatement(self::SIDEBAR_TITLE, $object->getSidebarTitle(), \PDO::PARAM_STR);
    }
    
    protected function getTable() {
        return self::TABLE;
    }
    
    protected function buildObject($result) {
        $sidebar = new Sidebar();
        $sidebar->setId(Arrays::get($result, self::COLUMN_ID));
        $sidebar->setSidebarTitle(Arrays::get($result, self::SIDEBAR_TITLE));
        $sidebar->setSidebarPosition(Arrays::get($result, self::SIDEBAR_POSITION));
        $sidebar->setSidebarContents(Arrays::get($result, self::SIDEBAR_CONTENTS));
        
        return $sidebar;
    }
    
}
