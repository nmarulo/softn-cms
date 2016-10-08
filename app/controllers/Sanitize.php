<?php
/**
 * Conjunto de filtros y ayudas para sanear los datos.
 */

namespace SoftnCMS\controllers;

/**
 * Class Sanitize
 * @author Nicolás Marulanda P.
 */
class Sanitize {
    
    public static function alphanumeric($value, $accents = TRUE, $withoutSpace = FALSE, $replaceSpace = '-') {
        $pattern = 'áéíóúÁÉÍÓÚ';
        
        if (!$accents || $withoutSpace) {
            $pattern = '';
        }
        
        //        $value = mb_convert_encoding($value, 'UTF-8', mb_detect_encoding($value, 'auto'));
        
        $pattern = '/[^a-zA-Z' . $pattern . '0-9\s]/';
        $output  = preg_replace($pattern, '', $value);
        $output  = preg_replace('/[\¡]/', '', $output);
        $output  = self::clearSpace($output);
        
        if ($withoutSpace) {
            $output = str_replace(' ', $replaceSpace, $output);
        }
        
        return $output;
    }
    
    public static function clearSpace($value) {
        return trim(preg_replace('/( ){2,}/', ' ', $value));
    }
    
    public static function clearTags($value) {
        return strip_tags($value);
    }
    
    public static function alphabetic($value, $accents = TRUE, $withoutSpace = FALSE, $replaceSpace = '-') {
        $pattern = 'áéíóúÁÉÍÓÚ';
        
        if (!$accents || $withoutSpace) {
            $pattern = '';
        }
        
        $output = preg_replace('/[^a-zA-Z' . $pattern . '\s]/', '', $value);
        $output = preg_replace('/[\¡]/', '', $output);
        $output = self::clearSpace($output);
        
        if ($withoutSpace) {
            $output = str_replace(' ', $replaceSpace, $output);
        }
        
        return $output;
    }
    
    public static function url($value) {
        return filter_var($value, FILTER_SANITIZE_URL);
    }
    
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
    
    public static function integer($value, $sign = FALSE) {
        $output = $value;
        
        if (!$sign) {
            $output = preg_replace('/[^0-9]/', '', $output);
            $output = abs(intval($output));
        }
        
        return filter_var($output, FILTER_SANITIZE_NUMBER_INT);
    }
    
    public static function float($value, $sign = FALSE) {
        //
        return filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }
    
    public static function email($value) {
        return filter_var($value, FILTER_SANITIZE_EMAIL);
    }
    
    public static function text($value) {
        return filter_var($value, FILTER_SANITIZE_STRING);
    }
}
