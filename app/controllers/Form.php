<?php

/**
 * Modulo: Formularios de la aplicación. Obtiene los datos y aplica las clases Sanitize, Validare y Escape según
 * corresponda.
 */

namespace SoftnCMS\controllers;

use SoftnCMS\Helpers\form\inputs\getters\InputGetters;

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
     * Método que agrega los datos a la lista de datos.
     *
     * @param InputGetters $input
     */
    public static function addInput($input) {
        self::$INPUT[] = $input;
    }
    
    /**
     * Método que establece la lista de datos.
     * Si previamente se uso la función "addInput", los datos serán reemplazados.
     *
     * @param array $INPUT
     */
    public static function setINPUT($INPUT) {
        self::$INPUT = $INPUT;
    }
    
    /**
     * Método que itera sobre la lista de datos, los comprueba y valida.
     * @return array|bool Retorna false en caso de error.
     * Retorna una lista donde su indice corresponde al nombre del campo "input" y su valor filtrado.
     */
    public static function inputFilter() {
        $output = [];
        $error  = FALSE;
        $len    = count(self::$INPUT);
        
        for ($i = 0; $i < $len && !$error; ++$i) {
            $data  = self::$INPUT[$i];
            $value = $data->filter();
            
            if ($value === '' && $data->isRequire()) {
                Messages::addError('Error. Los datos no son validos.');
                
                $error  = TRUE;
                $output = FALSE;
            } else {
                /*
                 * El nombre del campo corresponde al indice
                 * en la lista de datos a retornar.
                 */
                $output[$data->getName()] = $value;
            }
        }
        
        return $output;
    }
    
}
