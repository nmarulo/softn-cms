<?php
/**
 * ProfileLicense.php
 */

namespace SoftnCMS\models\licenses;

use SoftnCMS\models\LicenseAbstract;
use SoftnCMS\models\managers\ProfilesManager;

/**
 * Class ProfileLicense
 * @author Nicolás Marulanda P.
 */
class ProfileLicense extends LicenseAbstract {
    
    public static function getManagerClass() {
        return ProfilesManager::class;
    }
    
}
