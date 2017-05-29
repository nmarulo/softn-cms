<?php
/**
 * Util.php
 */

namespace SoftnCMS\util;

/**
 * Class Util
 * @author Nicolás Marulanda P.
 */
class Util {
    
    /**
     * Método que obtiene la fecha actual.
     *
     * @param string $format Formato de la fecha.
     *
     * @return false|string
     */
    public static function dateNow($format = 'Y-m-d H:i:s') {
        return date($format, time());
    }
    
}
