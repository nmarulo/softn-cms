<?php

/**
 * Modulo del modelo de las opciones configurables de la aplicación..
 * Gestiona grupos de opciones.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\models\admin\Option;
use SoftnCMS\models\admin\base\Models;

/**
 * Clase que gestiona grupos de opciones.
 *
 * @author Nicolás Marulanda P.
 */
class Options extends Models {

    /**
     * Constructor.
     */
    public function __construct() {
        parent::__construct(Option::getTableName(), __CLASS__);
    }

    /**
     * Metodo que obtiene todos las opciones de la base de datos.
     * @return Options
     */
    public static function selectAll() {
        $select = self::select(Option::getTableName());

        return self::getInstanceData($select);
    }

    /**
     * Metodo que recibe un lista de datos y retorna un instancia.
     * @param array $data Lista de datos.
     * @return Options|bool Si es FALSE, no hay datos.
     */
    public static function getInstanceData($data) {
        return parent::getInstance($data, __CLASS__);
    }
    
    /**
     * Metodo que obtiene el número total de datos.
     * @return int
     */
    public static function count() {
        return parent::countData(Option::getTableName());
    }

    /**
     * Metodo que obtiene, segun su nombre asignado, una opción.
     * @param string $optionName
     * @return Option
     */
    public function getByID($optionName) {
        return $this->data[$optionName];
    }

    /**
     * Metodo que agrega una opción a la lista.
     * @param Option $option
     */
    public function add($option) {
        $this->data[$option->getOptionName()] = $option;
    }

    /**
     * Metodo que recibe una lista de datos y los agrega a la lista actual.
     * @param array $data Lista de datos.
     */
    public function addData($data) {
        foreach ($data as $value) {
            $this->add(new Option($value));
        }
    }

}
