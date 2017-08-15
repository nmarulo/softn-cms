<?php
/**
 * SidebarsManager.php
 */

namespace SoftnCMS\models\managers;

use SoftnCMS\models\CRUDManagerAbstract;
use SoftnCMS\models\tables\Sidebar;
use SoftnCMS\util\Arrays;
use SoftnCMS\util\Messages;

/**
 * Class SidebarsManager
 * @author Nicolás Marulanda P.
 */
class SidebarsManager extends CRUDManagerAbstract {
    
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
    
    public function delete($id) {
        if (!parent::delete($id)) {
            return FALSE;
        }
        
        if (!$this->updatePositions()) {
            Messages::addDanger(__('Error al actualizar las posiciones de las barras laterales.'));
        }
        
        return TRUE;
    }
    
    private function updatePositions() {
        $sidebars = $this->read();
        
        $len      = count($sidebars);
        $notError = TRUE;
        
        for ($i = 0; $i < $len && $notError; ++$i) {
            $sidebar = Arrays::get($sidebars, $i);
            
            if (empty($sidebar)) {
                $notError = FALSE;
            } else {
                $position = $sidebar->getSidebarPosition();
                $sidebar->setSidebarPosition($i + 1);
                
                if ($position != $i + 1 && !$this->update($sidebar)) {
                    $notError = FALSE;
                }
            }
        }
        
        return $notError;
    }
    
    public function read($filters = []) {
        $table = $this->getTableWithPrefix();
        $query = sprintf('SELECT * FROM %1$s ORDER BY %2$s ASC', $table, self::SIDEBAR_POSITION);
        
        return parent::readData($query);
    }
    
    /**
     * @param Sidebar $object
     */
    protected function addParameterQuery($object) {
        parent::parameterQuery(self::SIDEBAR_CONTENTS, $object->getSidebarContents(), \PDO::PARAM_STR);
        parent::parameterQuery(self::SIDEBAR_POSITION, $object->getSidebarPosition(), \PDO::PARAM_INT);
        parent::parameterQuery(self::SIDEBAR_TITLE, $object->getSidebarTitle(), \PDO::PARAM_STR);
    }
    
    protected function getTable() {
        return self::TABLE;
    }
    
    protected function buildObjectTable($result) {
        parent::buildObjectTable($result);
        $sidebar = new Sidebar();
        $sidebar->setId(Arrays::get($result, self::ID));
        $sidebar->setSidebarTitle(Arrays::get($result, self::SIDEBAR_TITLE));
        $sidebar->setSidebarPosition(Arrays::get($result, self::SIDEBAR_POSITION));
        $sidebar->setSidebarContents(Arrays::get($result, self::SIDEBAR_CONTENTS));
        
        return $sidebar;
    }
    
}
