<?php
/**
 * OptionLicense.php
 */

namespace SoftnCMS\models\licenses;

use SoftnCMS\models\LicenseAbstract;
use SoftnCMS\models\managers\OptionsManager;

/**
 * Class OptionLicense
 * @author Nicolás Marulanda P.
 */
class OptionLicense extends LicenseAbstract {
    
    public static function getManagerClass() {
        return OptionsManager::class;
    }
}
