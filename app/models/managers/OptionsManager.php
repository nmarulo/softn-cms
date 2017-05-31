<?php
/**
 * OptionsManager.php
 */

namespace SoftnCMS\models\managers;

use SoftnCMS\models\CRUDManagerAbstract;
use SoftnCMS\util\MySQL;

/**
 * Class OptionsManager
 * @author Nicolás Marulanda P.
 */
class OptionsManager extends CRUDManagerAbstract {
    
    const TABLE        = 'options';
    
    const OPTION_NAME  = 'option_name';
    
    const OPTION_VALUE = 'option_value';
    
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
