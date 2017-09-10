<?php
/**
 * CommentLicense.php
 */

namespace SoftnCMS\models\licenses;

use SoftnCMS\models\LicenseAbstract;
use SoftnCMS\models\managers\CommentsManager;

/**
 * Class CommentLicense
 * @author Nicolás Marulanda P.
 */
class CommentLicense extends LicenseAbstract {
    
    public static function getManagerClass() {
        return CommentsManager::class;
    }
    
}
