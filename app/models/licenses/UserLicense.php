<?php
/**
 * UserLicense.php
 */

namespace SoftnCMS\models\licenses;

use SoftnCMS\models\LicenseAbstract;
use SoftnCMS\models\managers\UsersManager;

/**
 * Class UserLicense
 * @author Nicolás Marulanda P.
 */
class UserLicense extends LicenseAbstract {
    
    public static function getManagerClass() {
        return UsersManager::class;
    }
    
}
