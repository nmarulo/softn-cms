<?php
/**
 * ParseOf.php
 */

namespace App\Rest\Common;

use App\Facades\UtilsFacade;

/**
 * trait ParseOf
 * @author Nicolás Marulanda P.
 */
trait ParseOf {
    
    use ParseOfClass;
    
    /**
     * @param array $value
     *
     * @return mixed
     */
    public static function parseOf(array $value) {
        return UtilsFacade::parseOf($value, get_called_class());
    }
    
}
