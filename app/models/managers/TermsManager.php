<?php
/**
 * TermsManager.php
 */

namespace SoftnCMS\models\managers;

use SoftnCMS\models\CRUDManagerAbstract;
use SoftnCMS\models\tables\Term;
use SoftnCMS\util\Arrays;
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
    
    /**
     * @param Term $object
     */
    protected function addParameterQuery($object) {
        parent::parameterQuery(self::TERM_NAME, $object->getTermName(), \PDO::PARAM_STR);
        parent::parameterQuery(self::TERM_DESCRIPTION, $object->getTermDescription(), \PDO::PARAM_STR);
        parent::parameterQuery(self::TERM_COUNT, $object->getTermCount(), \PDO::PARAM_INT);
    }
    
    protected function getTable() {
        return self::TABLE;
    }
    
    protected function buildObjectTable($result) {
        parent::buildObjectTable($result);
        $term = new Term();
        $term->setId(Arrays::get($result, self::ID));
        $term->setTermName(Arrays::get($result, self::TERM_NAME));
        $term->setTermDescription(Arrays::get($result, self::TERM_DESCRIPTION));
        $term->setTermCount(Arrays::get($result, self::TERM_COUNT));
        
        return $term;
    }
    
}
