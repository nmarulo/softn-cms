<?php
/**
 * TermsManager.php
 */

namespace SoftnCMS\models\managers;

use SoftnCMS\models\CRUDManagerAbstract;
use SoftnCMS\util\MySQL;

/**
 * Class TermsManager
 * @author Nicolás Marulanda P.
 */
class TermsManager extends CRUDManagerAbstract {
    
    const TABLE            = 'terms';
    
    const TERM_NAME        = 'term_name';
    
    const TERM_DESCRIPTION = 'term_description';
    
    const TERM_COUNT       = 'term_count';
    
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
