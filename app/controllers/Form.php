<?php

/**
 * Modulo: Formularios de la aplicación. Obtiene los datos y aplica las clases Sanitize, Validare y Escape según
 * corresponda.
 */

namespace SoftnCMS\controllers;

use SoftnCMS\Helpers\ArrayHelp;

/**
 * Clase Form para los formularios de la aplicación.
 * @author Nicolás Marulanda P.
 */
class Form {
    
    /**
     * @var array Lista de datos.
     */
    private static $INPUT = [];
    
    /**
     * Método que comprueba si el nombre del indice existe.
     *
     * @param $name
     *
     * @return bool
     */
    public static function submit($name) {
        return isset($_POST[$name]) || isset($_GET[$name]);
    }
    
    /**
     * Método que lee los datos del formulario.
     * @return array|bool
     */
    public static function postInput() {
        $output = [];
        
        foreach (self::$INPUT as $data) {
            $value = ArrayHelp::get($_POST, $data['name']);
            
            $value = self::filter($data, $value);
            
            if ($value === FALSE && $data['type'] != 'checkbox' && $data['require']) {
                Messages::addError('Error. Los datos no son validos.');
                
                return FALSE;
            }
            /*
             * El nombre del campo corresponde al indice
             * en la lista de datos a retornar.
             */
            $output[$data['name']] = $value;
        }
        
        return $output;
    }
    
    /**
     * Método que aplica las clases Sanitize, Validare y Escape según corresponda.
     *
     * @param array  $data  Lista de datos.
     * @param string $value Dato.
     *
     * @return bool|mixed|string
     */
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
    
    /**
     * Método que agrega un campo de filtro "HTML" a la lista de datos.
     *
     * @param string   $name    Nombre del campo. Etiqueta "name".
     * @param bool     $require [Opcional]
     * @param bool|int $lenMax  [Opcional]
     * @param string   $type    [Opcional]
     * @param bool|int $lenMin  [Opcional]
     * @param bool     $accents [Opcional]
     */
    public static function addInputHtml($name, $require = FALSE, $lenMax = FALSE, $type = 'textarea', $lenMin = 1, $accents = TRUE) {
        self::addInput($name, $type, 'html', $lenMax, $require, $lenMin, FALSE, FALSE, $accents);
    }
    
    /**
     * Método que agrega los datos a la lista de datos.
     *
     * @param string   $name         Nombre del campo. Etiqueta "name".
     * @param string   $type         Tipo del campo. EJ: "text", "select", "url", "number".
     * @param string   $filter       Tipo de filtro. EJ: "email", "url", "html".
     * @param bool|int $lenMax       Longitud máxima del campo. Si es FALSE no se comprueba.
     * @param bool     $require      [Opcional]
     * @param bool|int $lenMin       [Opcional] Longitud mínima del campo. Si es FALSE no se comprueba.
     * @param bool     $arrayType    [Opcional] Tipo de datos del la lista. (Usado para la etiqueta Select multiple).
     * @param bool     $lenStrict    [Opcional] Longitud máxima estricta, es decir, que la longitud debe ser
     *                               exactamente la longitud máxima.
     * @param bool     $accents      [Opcional] Acentos.
     * @param bool     $withoutSpace [Opcional] Sin espacios.
     * @param string   $replaceSpace [Opcional] Carácter por el cual se reemplazaran los espacios.
     * @param bool     $sign         [Opcional] Signos (Para números).
     */
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
    
    /**
     * Método que agrega un campo de filtro "alphanumeric" a la lista de datos.
     *
     * @param string   $name         Nombre del campo. Etiqueta "name".
     * @param bool     $require      [Opcional]
     * @param bool|int $lenMax       [Opcional]
     * @param bool     $accents      [Opcional]
     * @param bool     $lenStrict    [Opcional]
     * @param bool|int $lenMin       [Opcional]
     * @param bool     $withoutSpace [Opcional]
     * @param string   $replaceSpace [Opcional]
     * @param string   $type         [Opcional]
     */
    public static function addInputAlphanumeric($name, $require = FALSE, $lenMax = FALSE, $accents = TRUE, $lenStrict = FALSE, $lenMin = 1, $withoutSpace = FALSE, $replaceSpace = '-', $type = 'text') {
        self::addInput($name, $type, 'alphanumeric', $lenMax, $require, $lenMin, FALSE, $lenStrict, $accents, $withoutSpace, $replaceSpace, FALSE);
    }
    
    /**
     * Método que agrega un campo de filtro "alphabetic" a la lista de datos.
     *
     * @param string   $name         Nombre del campo. Etiqueta "name".
     * @param bool     $require      [Opcional]
     * @param bool|int $lenMax       [Opcional]
     * @param bool     $accents      [Opcional]
     * @param bool     $lenStrict    [Opcional]
     * @param bool|int $lenMin       [Opcional]
     * @param bool     $withoutSpace [Opcional]
     * @param string   $replaceSpace [Opcional]
     * @param string   $type         [Opcional]
     */
    public static function addInputAlphabetic($name, $require = FALSE, $lenMax = FALSE, $accents = TRUE, $lenStrict = FALSE, $lenMin = 1, $withoutSpace = FALSE, $replaceSpace = '-', $type = 'text') {
        self::addInput($name, $type, 'alphabetic', $lenMax, $require, $lenMin, FALSE, $lenStrict, $accents, $withoutSpace, $replaceSpace, FALSE);
    }
    
    /**
     * Método que agrega un campo de filtro "integer" a la lista de datos.
     *
     * @param string   $name      Nombre del campo. Etiqueta "name".
     * @param bool     $require   [Opcional]
     * @param bool|int $lenMax    [Opcional]
     * @param string   $type      [Opcional]
     * @param bool     $lenStrict [Opcional]
     * @param bool|int $lenMin    [Opcional]
     * @param bool     $sign      [Opcional]
     */
    public static function addInputInteger($name, $require = FALSE, $lenMax = FALSE, $type = 'number', $lenStrict = FALSE, $lenMin = 1, $sign = FALSE) {
        self::addInput($name, $type, 'integer', $lenMax, $require, $lenMin, FALSE, $lenStrict, FALSE, TRUE, '', $sign);
    }
    
    /**
     * Método que agrega un campo de filtro "boolean" a la lista de datos.
     *
     * @param string $name    Nombre del campo. Etiqueta "name".
     * @param bool   $require [Opcional]
     * @param string $type    [Opcional]
     */
    public static function addInputBoolean($name, $require = FALSE, $type = 'checkbox') {
        self::addInput($name, $type, 'boolean', FALSE, $require, 1, FALSE, FALSE, FALSE, FALSE, '', FALSE);
    }
    
    /**
     * Método que agrega un campo de filtro "arrayList" a la lista de datos.
     *
     * @param string   $name         Nombre del campo. Etiqueta "name".
     * @param bool     $require      [Opcional]
     * @param bool|int $lenMax       [Opcional]
     * @param string   $arrayType    [Opcional]
     * @param bool|int $lenMin       [Opcional]
     * @param bool     $lenStrict    [Opcional]
     * @param bool     $accents      [Opcional]
     * @param bool     $withoutSpace [Opcional]
     * @param string   $replaceSpace [Opcional]
     * @param bool     $sign         [Opcional]
     * @param string   $type         [Opcional]
     */
    public static function addInputArrayList($name, $require = FALSE, $lenMax = FALSE, $arrayType = 'integer', $lenMin = 1, $lenStrict = FALSE, $accents = TRUE, $withoutSpace = FALSE, $replaceSpace = '-', $sign = FALSE, $type = 'select') {
        self::addInput($name, $type, 'arrayList', $lenMax, $require, $lenMin, $arrayType, $lenStrict, $accents, $withoutSpace, $replaceSpace, $sign);
    }
    
    /**
     * Método que agrega un campo de filtro "email" a la lista de datos.
     *
     * @param string   $name    Nombre del campo. Etiqueta "name".
     * @param bool     $require [Opcional]
     * @param bool|int $lenMax  [Opcional]
     * @param bool|int $lenMin  [Opcional]
     * @param string   $type    [Opcional]
     */
    public static function addInputEmail($name, $require = FALSE, $lenMax = FALSE, $lenMin = 1, $type = 'email') {
        self::addInput($name, $type, 'email', $lenMax, $require, $lenMin, FALSE, FALSE, FALSE, TRUE, '', FALSE);
    }
    
    /**
     * Método que agrega un campo de filtro "url" a la lista de datos.
     *
     * @param string   $name    Nombre del campo. Etiqueta "name".
     * @param bool     $require [Opcional]
     * @param bool|int $lenMax  [Opcional]
     * @param bool|int $lenMin  [Opcional]
     * @param string   $type    [Opcional]
     */
    public static function addInputUrl($name, $require = FALSE, $lenMax = FALSE, $lenMin = 1, $type = 'url') {
        self::addInput($name, $type, 'url', $lenMax, $require, $lenMin, FALSE, FALSE, FALSE, TRUE, '', FALSE);
    }
    
}
