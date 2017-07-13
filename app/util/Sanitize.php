<?php
/**
 * Modulo: Filtros y ayudas para sanear los datos.
 */

namespace SoftnCMS\util;

/**
 * Clase Sanitize con filtros y ayudas para sanear los datos.
 * @author Nicolás Marulanda P.
 */
class Sanitize {
    
    /**
     * Método que filtra valores alfanuméricos.
     *
     * @param string $value
     * @param bool   $accents      [Opcional] Acentos.
     * @param bool   $withoutSpace [Opcional] Sin espacios
     * @param string $replaceSpace [Opcional] Carácter por el cual se reemplazaran los espacios.
     * @param bool   $specialChar
     *
     * @return mixed|string
     */
    public static function alphanumeric($value, $accents = TRUE, $withoutSpace = FALSE, $replaceSpace = '-', $specialChar = FALSE) {
        return self::filter($value, Util::getPatternAlphanumeric(), $accents, $withoutSpace, $replaceSpace, $specialChar);
    }
    
    private static function filter($value, $pattern, $accents, $withoutSpace, $replaceSpace, $specialChar) {
        if ($accents) {
            $pattern .= Util::getPatternAccents();
        }
        
        if($specialChar){
            $pattern .= Util::getPatternSpecialChar();
        }
        
        $output = preg_replace([
            '/[^' . $pattern . ']+/',
            '/[¡]+/',
        ], '', $value);
        $output = self::clearSpace($output);
        
        if ($withoutSpace) {
            $output = str_replace(' ', $replaceSpace, $output);
        }
        
        return $output;
    }
    
    /**
     * Método que borra dos o mas espacios seguidos de una cadena de caracteres.
     *
     * @param string $value
     *
     * @return string
     */
    public static function clearSpace($value) {
        return trim(preg_replace('/( ){2,}/', ' ', $value));
    }
    
    /**
     * Método que retira las etiquetas HTML y PHP de un string.
     *
     * @param string $value
     *
     * @return string
     */
    public static function clearTags($value) {
        return strip_tags($value);
    }
    
    /**
     * Método que filtra valores alfabéticos.
     *
     * @param string $value
     * @param bool   $accents      [Opcional] Acentos.
     * @param bool   $withoutSpace [Opcional] Sin espacios
     * @param string $replaceSpace [Opcional] Carácter por el cual se reemplazaran los espacios.
     * @param bool   $specialChar
     *
     * @return mixed|string
     */
    public static function alphabetic($value, $accents = TRUE, $withoutSpace = FALSE, $replaceSpace = '-', $specialChar = FALSE) {
        return self::filter($value, Util::getPatternAlphabetic(), $accents, $withoutSpace, $replaceSpace, $specialChar);
    }
    
    /**
     * Método que elimina todos los caracteres no permitidos según el filtro de saneamiento "FILTER_SANITIZE_URL".
     *
     * @param string $value
     *
     * @return string
     */
    public static function url($value) {
        return trim(filter_var($value, FILTER_SANITIZE_URL), '/') . '/';
    }
    
    /**
     * Método filtra una lista de datos.
     *
     * @param array  $value
     * @param string $type [Opcional] Tipo de datos.
     * @param bool   $sign [Opcional] Signos (Para números).
     *
     * @return array
     */
    public static function arrayList($value, $type = 'integer', $sign = FALSE) {
        $output = [];
        
        if (!is_array($value)) {
            return $output;
        }
        
        foreach ($value as $key => $data) {
            $num = 0;
            
            switch ($type) {
                case 'integer':
                    $num = self::integer($data, $sign);
                    break;
                case 'float':
                    $num = self::float($data, $sign);
                    break;
            }
            
            $output[$key] = $num;
        }
        
        return $output;
    }
    
    /**
     * Método que filtra valores numéricos enteros.
     *
     * @param string|int $value
     * @param bool       $sign [Opcional] Signos.
     *
     * @return mixed
     */
    public static function integer($value, $sign = FALSE) {
        $output = $value;
        
        if (!$sign) {
            $output = preg_replace('/[^0-9]/', '', $output);
            $output = abs(intval($output));
        }
        
        return filter_var($output, FILTER_SANITIZE_NUMBER_INT);
    }
    
    /**
     * Método que filtra valores numéricos decimales.
     *
     * @param string|float $value
     * @param bool         $sign [Opcional] Signos.
     *
     * @return mixed
     */
    public static function float($value, $sign = FALSE) {
        //
        return filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }
    
    /**
     * Método que filtra direcciones de correo electrónico.
     *
     * @param string $value
     *
     * @return mixed
     */
    public static function email($value) {
        return filter_var($value, FILTER_SANITIZE_EMAIL);
    }
    
    /**
     * Método que filtra texto según el filtro de saneamiento "FILTER_SANITIZE_STRING".
     *
     * @param string $value
     *
     * @return mixed
     */
    public static function text($value) {
        return filter_var($value, FILTER_SANITIZE_STRING);
    }
}
