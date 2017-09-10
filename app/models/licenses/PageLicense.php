<?php
/**
 * PageLicense.php
 */

namespace SoftnCMS\models\licenses;

use SoftnCMS\models\LicenseAbstract;
use SoftnCMS\models\managers\PagesManager;

/**
 * Class PageLicense
 * @author Nicolás Marulanda P.
 */
class PageLicense extends LicenseAbstract {
    
    public static function getManagerClass() {
        return PagesManager::class;
    }
    
}
