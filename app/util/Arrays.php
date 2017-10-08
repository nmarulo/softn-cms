<?php
/**
 * Arrays.php
 */

namespace SoftnCMS\util;

/**
 * Class Arrays
 * @author Nicolás Marulanda P.
 */
class Arrays {
    
    public static function valueExists($array, $value) {
        return is_array($array) && array_search($value, $array) !== FALSE;
    }
    
    /**
     * @param array $array
     *
     * @return bool|mixed
     */
    public static function findFirst($array) {
        if (!is_array($array)) {
            return FALSE;
        }
        
        $result = array_shift($array);
        
        if (is_array($result)) {
            $result = self::findFirst($result);
        }
        
        return $result;
    }
    
    /**
     * Método que obtiene el valor de un array según su indice.
     *
     * @param array      $array
     * @param string|int $key Indice.
     *
     * @return bool|mixed Retorna FALSE si no es un array o el indice no existe.
     */
    public static function get($array, $key) {
        if (self::keyExists($array, $key)) {
            return $array[$key];
        }
        
        return FALSE;
    }
    
    public static function keyExists($array, $key) {
        return is_array($array) && array_key_exists($key, $array);
    }
}
