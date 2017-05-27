<?php

/**
 * Modulo: Escapa los datos.
 */

namespace SoftnCMS\util;

/**
 * Clase Escape para escapar los datos.
 * @author Nicolás Marulanda P.
 */
class Escape {
    
    /**
     * Método que convierte entidades HTML especiales de nuevo en caracteres.
     *
     * @param $value
     *
     * @return string
     */
    public static function htmlDecode($value) {
        return htmlspecialchars_decode($value, ENT_QUOTES);
    }
    
    /**
     * Método que convierte caracteres especiales en entidades HTML
     *
     * @param $value
     *
     * @return string
     */
    public static function htmlEncode($value) {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8', TRUE);
    }
    
    /**
     * Método que codificar estilo URL de acuerdo al RFC 3986
     *
     * @param $value
     *
     * @return string
     */
    public static function url($value) {
        
        return rawurlencode($value);
    }
    
    /**
     * NOTA: Sin implementar.
     * Método que codifica CSS.
     *
     * @param $value
     *
     * @return string
     */
    public static function css($value) {
        return $value;
    }
    
    /**
     * NOTA: Sin implementar.
     * Método que codifica JS.
     *
     * @param $value
     *
     * @return string
     */
    public static function js($value) {
        return $value;
    }
    
}
