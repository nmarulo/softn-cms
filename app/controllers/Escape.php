<?php
/**
 * Compronente que escapa los datos e intenta asegurar la seguridad de la aplicación.
 */

namespace SoftnCMS\controllers;

/**
 * Class Escape
 * @author Nicolás Marulanda P.
 */
class Escape {
    
    public static function htmlDecode($value) {
        return htmlspecialchars_decode($value, ENT_QUOTES);
    }
    
    public static function htmlEncode($value) {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8', TRUE);
    }
    
    public static function url($value) {
        
        return rawurlencode($value);
    }
    
    public static function css() {
        
    }
    
    public static function js() {
        
    }
    
}
