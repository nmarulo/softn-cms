<?php
/**
 * PostsLicense.php
 */

namespace SoftnCMS\models\licenses;

use SoftnCMS\models\LicenseAbstract;
use SoftnCMS\models\managers\PostsManager;

/**
 * Class PostsLicense
 * @author Nicolás Marulanda P.
 */
class PostLicense extends LicenseAbstract {
    
    public static function getManagerClass() {
        return PostsManager::class;
    }
    
}
