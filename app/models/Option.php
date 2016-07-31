<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SoftnCMS\models;

/**
 * Description of Option
 *
 * @author Nicolás Marulanda P.
 */
class Option {
    
    /** @var string Nombre de la table. */
    private static $TABLE = \DB_PREFIX . 'options';
    
    /** Identificador. */
    const ID = 'ID';

    /** Nombre asignado. */
    const OPTION_NAME = 'option_name';

    /** Valor. */
    const OPTION_VALUE = 'option_value';
    
    /**
     * Datos.
     * @var array 
     */
    private $option;
    
    /**
     * Constructor.
     * @param array $data
     */
    public function __construct($data) {
        $this->option = $data;
    }
    
    /**
     * Metodo que obtiene el nombre de la tabla.
     * @return string
     */
    public static function getTableName(){
        return self::$TABLE;
    }
    
    /**
     * Metodo que obtiene el identificador.
     * @return int
     */
    public function getID(){
        return $this->option[Option::ID];
    }
    
    /**
     * Metodo que obtiene el nombre asignado.
     * @return string
     */
    public function getOptionName(){
        return $this->option[Option::OPTION_NAME];
    }
    
    /**
     * Metodo que obtiene el valor.
     * @return string
     */
    public function getOptionValue(){
        return $this->option[Option::OPTION_VALUE];
    }
    
}
