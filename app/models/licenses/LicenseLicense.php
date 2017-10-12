<?php
/**
 * LicenseLicense.php
 */

namespace SoftnCMS\models\licenses;

use SoftnCMS\models\LicenseAbstract;
use SoftnCMS\models\managers\LicensesManager;

/**
 * Class LicenseLicense
 * @author Nicolás Marulanda P.
 */
class LicenseLicense extends LicenseAbstract {
    
    public static function getManagerClass() {
        return LicensesManager::class;
    }
    
}
