<?php
/**
 * SidebarLicense.php
 */

namespace SoftnCMS\models\licenses;

use SoftnCMS\models\LicenseAbstract;
use SoftnCMS\models\managers\SidebarsManager;

/**
 * Class SidebarLicense
 * @author Nicolás Marulanda P.
 */
class SidebarLicense extends LicenseAbstract {
    
    public static function getManagerClass() {
        return SidebarsManager::class;
    }
    
}
