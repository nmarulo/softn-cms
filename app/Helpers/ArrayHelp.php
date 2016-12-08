<?php
/**
 * Modulo: Operaciones comunes en arrays.
 */

namespace SoftnCMS\helpers;

/**
 * Clase ArrayHelp
 * @author Nicolás Marulanda P.
 */
class ArrayHelp {
    
    /**
     * Método que obtiene el valor de un array según su indice.
     *
     * @param $array
     * @param $key
     *
     * @return bool|mixed Retorna FALSE si no es un array o el indice no existe.
     */
    public static function get($array, $key) {
        if (!is_array($array) || !array_key_exists($key, $array)) {
            return FALSE;
        }
        
        return $array[$key];
    }
}
