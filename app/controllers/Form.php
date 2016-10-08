<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SoftnCMS\controllers;

/**
 * Description of Form
 * @author Nicolás Marulanda P.
 */
class Form {
    
    private static $VALIDATE = FALSE;
    
    private static $INPUT    = [];
    
    public static function submit($name) {
        return isset($_POST[$name]) || isset($_GET[$name]);
    }
    
    public static function postInput() {
        $output = [];
        
        foreach (self::$INPUT as $value) {
            $dataInput = isset($_POST[$value['name']]) ? $_POST[$value['name']] : NULL;
            $dataInput = self::filter($value, $dataInput);
            
            if ($dataInput === FALSE && $value['type'] != 'checkbox' && $value['require']) {
                return FALSE;
            }
            $output[$value['name']] = $dataInput;
        }
        
        self::$VALIDATE = TRUE;
        
        return $output;
    }
    
    public static function filter($data, $value) {
        $output = FALSE;
        
        if ($value === NULL) {
            return FALSE;
        }
        
        switch ($data['filter']) {
            case 'integer':
                $output = Sanitize::integer($value);
                
                if (!Validate::integer($value, $data['length']['max'], $data['length']['strict'])) {
                    $output = FALSE;
                }
                break;
            case 'alphanumeric':
                $output = Sanitize::alphanumeric($value);
                
                if (!Validate::alphanumeric($output, $data['length']['max'], $data['accents'], $data['length']['strict'])) {
                    $output = FALSE;
                }
                break;
            case 'alphabetic':
                $output = Sanitize::alphabetic($value);
                
                if (!Validate::alphabetic($output, $data['length']['max'], $data['accents'], $data['length']['strict'])) {
                    $output = FALSE;
                }
                break;
            case 'html':
                $output = Escape::htmlEncode($value);
                break;
            case 'boolean':
                $output = Validate::boolean($value);
                break;
            case 'arrayList':
                $output = Sanitize::arrayList($value, $data['arrayType'], $data['sign']);
                break;
        }
        
        return $output;
    }
    
    public static function addInput($name, $type, $filter, $lenMax, $require = TRUE, $lenMin = 0, $arrayType = FALSE, $lenStrict = FALSE, $accents = TRUE, $withoutSpace = FALSE, $replaceSpace = '-', $sign = FALSE) {
        self::$INPUT[] = [
            'filter'    => $filter,
            //tipo de input
            'type'      => $type,
            'require'   => $require,
            'name'      => $name,
            'length'    => [
                'min'    => $lenMin,
                'max'    => $lenMax,
                'strict' => $lenStrict,
            ],
            'accents'   => $accents,
            'space'     => [
                'without' => $withoutSpace,
                'replace' => $replaceSpace,
            ],
            'sign'      => $sign,
            'arrayType' => $arrayType,
        ];
    }
    
    public static function isValidate() {
        return self::$VALIDATE;
    }
    
}
