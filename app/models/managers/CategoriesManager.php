<?php
/**
 * CategoriesManager.php
 */

namespace SoftnCMS\models\managers;

use SoftnCMS\models\CRUDManagerAbstract;
use SoftnCMS\models\tables\Category;
use SoftnCMS\util\MySQL;

/**
 * Class CategoriesManager
 * @author Nicolás Marulanda P.
 */
class CategoriesManager extends CRUDManagerAbstract {
    
    const TABLE                = 'categories';
    
    const CATEGORY_NAME        = 'category_name';
    
    const CATEGORY_DESCRIPTION = 'category_description';
    
    const CATEGORY_COUNT       = 'category_count';
    
    protected function addParameterQuery($object) {
        // TODO: Implement addParameterQuery() method.
    }
    
    protected function getTable() {
        return self::TABLE;
    }
    
    protected function buildObjectTable($result, $fetch = MySQL::FETCH_ALL) {
        if (empty($result)) {
            throw new \Exception('Error');
        }
        
        $category = new Category();
        
        return $category;
    }
    
}
