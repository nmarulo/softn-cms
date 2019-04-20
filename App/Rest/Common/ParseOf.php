<?php
/**
 * ParseOf.php
 */

namespace App\Rest\Common;

/**
 * Interface ParseOf
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
        throw new \RuntimeException("Método no implementado.");
    }
    
}
