<?php
/**
 * CategoryLicense.php
 */

namespace SoftnCMS\models\licenses;

use SoftnCMS\models\managers\CategoriesManager;
use SoftnCMS\route\Route;

/**
 * Class CategoryLicense
 * @author Nicolás Marulanda P.
 */
class CategoryLicense extends License {
    
    public function __construct(Route $route, $userId) {
        parent::__construct($route, CategoriesManager::class, $userId);
    }
    
}
