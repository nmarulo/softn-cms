<?php
/**
 * SidebarsManager.php
 */

namespace SoftnCMS\models\managers;

use SoftnCMS\models\CRUDManagerAbstract;
use SoftnCMS\models\tables\Sidebar;
use SoftnCMS\util\Arrays;
use SoftnCMS\util\MySQL;

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
