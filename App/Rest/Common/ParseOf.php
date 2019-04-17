<?php
/**
 * ParseOf.php
 */

namespace App\Rest\Common;

/**
 * Interface ParseOf
 * @author Nicolás Marulanda P.
 */
interface ParseOf extends ParseOfClass {
    
    /**
     * @param array $value
     *
     * @return mixed
     */
    public static function parseOf(array $value);
    
}
