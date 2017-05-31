<?php
/**
 * SidebarsManager.php
 */

namespace SoftnCMS\models\managers;

use SoftnCMS\models\CRUDManagerAbstract;
use SoftnCMS\util\MySQL;

/**
 * Class SidebarsManager
 * @author Nicolás Marulanda P.
 */
class SidebarsManager extends CRUDManagerAbstract {
    
    const TABLE = 'sidebars';
    
    protected function addParameterQuery($object) {
        // TODO: Implement addParameterQuery() method.
    }
    
    protected function getTable() {
        // TODO: Implement getTable() method.
    }
    
    protected function buildObjectTable($result, $fetch = MySQL::FETCH_ALL) {
        // TODO: Implement buildObjectTable() method.
    }
    
}
