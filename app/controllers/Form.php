<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SoftnCMS\controllers;

use SoftnCMS\Helpers\ArrayHelp;

/**
 * Description of Form
 * @author Nicolás Marulanda P.
 */
class Form {
    
    private static $INPUT = [];
    
    public static function submit($name) {
        return isset($_POST[$name]) || isset($_GET[$name]);
    }
    
    public static function postInput() {
        $output = [];
        
        foreach (self::$INPUT as $data) {
            $value = ArrayHelp::get($_POST, $data['name']);
            
            $value = self::filter($data, $value);
            
            if ($value === FALSE && $data['type'] != 'checkbox' && $data['require']) {
                Messages::addError('Error. Los datos no son validos.');
                
                return FALSE;
            }
            
            $output[$data['name']] = $value;
        }
        
        return $output;
    }
    
    public static function filter($data, $value) {
        $output = FALSE;
        
        if ($value === FALSE) {
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
            case 'email':
                $output = Sanitize::email($value);
                
                if (!Validate::email($value)) {
                    $output = FALSE;
                }
                break;
            case 'url':
                $output = Sanitize::url($value);
                
                if (!Validate::url($output)) {
                    $output = FALSE;
                }
                break;
        }
        
        return $output;
    }
    
    public static function addInputHtml($name, $require = FALSE, $lenMax = FALSE, $type = 'textarea', $lenMin = 1, $accents = TRUE) {
        self::addInput($name, $type, 'html', $lenMax, $require, $lenMin, FALSE, FALSE, $accents);
    }
    
    public static function addInput($name, $type, $filter, $lenMax, $require = TRUE, $lenMin = 1, $arrayType = FALSE, $lenStrict = FALSE, $accents = TRUE, $withoutSpace = FALSE, $replaceSpace = '-', $sign = FALSE) {
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
    
    public static function addInputAlphanumeric($name, $require = FALSE, $lenMax = FALSE, $accents = TRUE, $lenStrict = FALSE, $lenMin = 1, $withoutSpace = FALSE, $replaceSpace = '-', $type = 'text') {
        self::addInput($name, $type, 'alphanumeric', $lenMax, $require, $lenMin, FALSE, $lenStrict, $accents, $withoutSpace, $replaceSpace, FALSE);
    }
    
    public static function addInputAlphabetic($name, $require = FALSE, $lenMax = FALSE, $accents = TRUE, $lenStrict = FALSE, $lenMin = 1, $withoutSpace = FALSE, $replaceSpace = '-', $type = 'text') {
        self::addInput($name, $type, 'alphabetic', $lenMax, $require, $lenMin, FALSE, $lenStrict, $accents, $withoutSpace, $replaceSpace, FALSE);
    }
    
    public static function addInputInteger($name, $require = FALSE, $lenMax = FALSE, $type = 'number', $lenStrict = FALSE, $lenMin = 1, $sign = FALSE) {
        self::addInput($name, $type, 'integer', $lenMax, $require, $lenMin, FALSE, $lenStrict, FALSE, TRUE, '', $sign);
    }
    
    public static function addInputBoolean($name, $require = FALSE, $type = 'checkbox') {
        self::addInput($name, $type, 'boolean', FALSE, $require, 1, FALSE, FALSE, FALSE, FALSE, '', FALSE);
    }
    
    public static function addInputArrayList($name, $require = FALSE, $lenMax = FALSE, $arrayType = 'integer', $lenMin = 1, $lenStrict = FALSE, $accents = TRUE, $withoutSpace = FALSE, $replaceSpace = '-', $sign = FALSE, $type = 'select') {
        self::addInput($name, $type, 'arrayList', $lenMax, $require, $lenMin, $arrayType, $lenStrict, $accents, $withoutSpace, $replaceSpace, $sign);
    }
    
    public static function addInputEmail($name, $require = FALSE, $lenMax = FALSE, $lenMin = 1, $type = 'email') {
        self::addInput($name, $type, 'email', $lenMax, $require, $lenMin, FALSE, FALSE, FALSE, TRUE, '', FALSE);
    }
    
    public static function addInputUrl($name, $require = FALSE, $lenMax = FALSE, $lenMin = 1, $type = 'url') {
        self::addInput($name, $type, 'url', $lenMax, $require, $lenMin, FALSE, FALSE, FALSE, TRUE, '', FALSE);
    }
    
}
