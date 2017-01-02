<?php
/**
 * Modulo: Validación de datos.
 */

namespace SoftnCMS\controllers;

/**
 * Clase Validate
 * @author Nicolás Marulanda P.
 */
class Validate {
    
    /**
     * Método que valida una cadena de caracteres alfanumérica.
     *
     * @param string   $value
     * @param bool|int $length    Longitud máxima. Si es FALSE no se comprueba.
     * @param bool     $accents   [Opcional]   Acentos.
     * @param bool     $lenStrict [Opcional] Longitud máxima estricta, es decir, que la longitud debe ser exactamente
     *                            la longitud máxima.
     *
     * @return bool
     */
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
    
    /**
     * Método que comprueba la longitud de una cadena de caracteres.
     *
     * @param string|int $value
     * @param bool|int   $length
     * @param bool       $lenStrict
     *
     * @return bool
     */
    public static function length($value, $length, $lenStrict = FALSE) {
        if ($length === 0 || ($lenStrict && mb_strlen($value) == $length) || (!$lenStrict && mb_strlen($value) <= $length)) {
            return TRUE;
        }
        
        return FALSE;
    }
    
    /**
     * Método que valida una cadena de caracteres alfabética.
     *
     * @param string   $value
     * @param bool|int $length
     * @param bool     $accents
     * @param bool     $lenStrict
     *
     * @return bool
     */
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
    
    /**
     * Método que valida valores numéricos enteros.
     *
     * @param string|int $value
     * @param bool|int   $length
     * @param bool       $lenStrict
     *
     * @return bool
     */
    public static function integer($value, $length, $lenStrict = FALSE) {
        $value = preg_replace('/[^0-9\.]/', '', $value);
        
        if (!ctype_digit($value) || !self::length($value, $length, $lenStrict)) {
            return FALSE;
        }
        
        return TRUE;
    }
    
    /**
     * Método que valida correos electrónicos.
     *
     * @param string $value
     *
     * @return bool
     */
    public static function email($value) {
        return filter_var($value, FILTER_VALIDATE_EMAIL) == $value;
    }
    
    /**
     * Método que valida direcciones url.
     *
     * @param string $value
     *
     * @return bool
     */
    public static function url($value) {
        return filter_var($value, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED) == $value;
    }
    
    /**
     * Método que valida valores booleanos.
     *
     * @param string|bool $value
     *
     * @return mixed
     */
    public static function boolean($value) {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
    
}
