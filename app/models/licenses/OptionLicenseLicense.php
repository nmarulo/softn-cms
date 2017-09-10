<?php
/**
 * OptionLicenseLicense.php
 */

namespace SoftnCMS\models\licenses;

use SoftnCMS\models\LicenseAbstract;
use SoftnCMS\models\managers\OptionsLicensesManager;

/**
 * Class OptionLicenseLicense
 * @author Nicolás Marulanda P.
 */
class OptionLicenseLicense extends LicenseAbstract {
    
    public static function getManagerClass() {
        return OptionsLicensesManager::class;
    }
    
}
