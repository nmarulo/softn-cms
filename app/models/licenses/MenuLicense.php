<?php
/**
 * MenuLicense.php
 */

namespace SoftnCMS\models\licenses;

use SoftnCMS\models\LicenseAbstract;
use SoftnCMS\models\managers\MenusManager;

/**
 * Class MenuLicense
 * @author Nicolás Marulanda P.
 */
class MenuLicense extends LicenseAbstract {
    
    public static function getManagerClass() {
        return MenusManager::class;
    }
}
