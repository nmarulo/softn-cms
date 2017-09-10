<?php
/**
 * CategoryLicense.php
 */

namespace SoftnCMS\models\licenses;

use SoftnCMS\models\LicenseAbstract;
use SoftnCMS\models\managers\CategoriesManager;

/**
 * Class CategoryLicense
 * @author Nicolás Marulanda P.
 */
class CategoryLicense extends LicenseAbstract {
    
    public static function getManagerClass() {
        return CategoriesManager::class;
    }
}
