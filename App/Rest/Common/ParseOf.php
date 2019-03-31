<?php
/**
 * ParseOf.php
 */

namespace App\Rest\Common;

/**
 * Interface ParseOf
 * @author MaruloPC-Desk
 */
interface ParseOf extends ParseOfClass {
    
    /**
     * @param array $value
     *
     * @return mixed
     */
    public static function parseOf(array $value);
    
}
