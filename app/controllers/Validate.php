<?php
/**
 * Comprueba si los datos son validos.
 */

namespace SoftnCMS\controllers;

/**
 * Class Validate
 * @author Nicolás Marulanda P.
 */
class Validate {
    
    public static function alphanumeric($value, $length, $accents = TRUE, $lenStrict = FALSE) {
        $pattern = 'áéíóúÁÉÍÓÚ';
        
        if (!$accents) {
            $pattern = '';
        }
        
        $pattern = '/^[a-zA-Z' . $pattern . '0-9\s]+$/';
        
        if (!is_string($value) || !preg_match($pattern, $value) || !self::length($value, $length, $lenStrict)) {
            return FALSE;
        }
        
        return TRUE;
    }
    
    public static function length($value, $length, $lenStrict = FALSE) {
        if ($length === FALSE || ($lenStrict && mb_strlen($value) == $length) || (!$lenStrict && mb_strlen($value) <= $length)) {
            return TRUE;
        }
        
        return FALSE;
    }
    
    public static function alphabetic($value, $length, $accents = TRUE, $lenStrict = FALSE) {
        $pattern = 'áéíóúÁÉÍÓÚ';
        
        if (!$accents) {
            $pattern = '';
        }
        
        $pattern = '/^[a-zA-Z' . $pattern . '0-9\s]+$/';
        
        if (!is_string($value) || !preg_match($pattern, $value) || !self::length($value, $length, $lenStrict)) {
            return FALSE;
        }
        
        return TRUE;
    }
    
    public static function integer($value, $length, $lenStrict = FALSE) {
        if (!ctype_digit($value) || !self::length($value, $length, $lenStrict)) {
            return FALSE;
        }
        
        return TRUE;
    }
    
    public static function email($value) {
        return filter_var($value, FILTER_VALIDATE_EMAIL) == $value;
    }
    
    public static function url($value) {
        return filter_var($value, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED) == $value;
    }
    
    public static function boolean($value) {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
    
}
