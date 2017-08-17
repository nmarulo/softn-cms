<?php

/**
 * Modulo: Formularios de la aplicación. Obtiene los datos y aplica las clases Sanitize, Validare y Escape según
 * corresponda.
 */

namespace SoftnCMS\util\form;

use SoftnCMS\util\form\inputs\Input;
use SoftnCMS\util\Messages;
use SoftnCMS\util\Token;

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
        Token::generate();
        //TODO: temporalmente, si estamos instalando la aplicación no se comprobara el token.
        return (isset($_POST[$name]) || isset($_GET[$name])) && (!defined('INSTALL') || Token::check());
    }
    
    /**
     * Método que agrega los datos a la lista de datos.
     *
     * @param Input $input
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
    public static function setInput($INPUT) {
        self::$INPUT = $INPUT;
    }
    
    /**
     * Método que itera sobre la lista de datos, los comprueba y valida.
     * @return array|bool Retorna false en caso de error.
     * Retorna una lista donde su indice corresponde al nombre del campo "input" y su valor filtrado.
     */
    public static function inputFilter() {
        $output   = [];
        $notError = TRUE;
        $len      = count(self::$INPUT);
        
        for ($i = 0; $i < $len && $notError; ++$i) {
            $data  = self::$INPUT[$i];
            $value = $data->filter();
            
            if ($value === '' && $data->isRequire()) {
                Messages::addDanger(__('El campo "%1$s" es obligatorio.', $data->getName()));
                $notError = FALSE;
                $output   = FALSE;
                Token::regenerate();
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
