<?php
/**
 * TermLicense.php
 */

namespace SoftnCMS\models\licenses;

use SoftnCMS\models\LicenseAbstract;
use SoftnCMS\models\managers\TermsManager;

/**
 * Class TermLicense
 * @author Nicolás Marulanda P.
 */
class TermLicense extends LicenseAbstract {
    
    public static function getManagerClass() {
        return TermsManager::class;
    }
    
}
