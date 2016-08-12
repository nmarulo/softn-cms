<?php

/**
 * Modulo del modelo de las opciones configurables de la aplicación..
 * Gestiona grupos de opciones.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\models\admin\Option;
use SoftnCMS\controllers\DBController;

/**
 * Clase que gestiona grupos de opciones.
 *
 * @author Nicolás Marulanda P.
 */
class Options {

    /**
     * Lista, donde el indice o clave corresponde al nombre asignado.
     * @var array 
     */
    private $options;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->options = [];
    }

    /**
     * Metodo que obtiene todos las opciones de la base de datos.
     * @return Options
     */
    public static function selectAll() {
        return self::select();
    }

    /**
     * Metodo que realiza una consulta a la base de datos.
     * @param string $where [Opcional] Condiciones.
     * @param array $prepare [Opcional] Lista de indices a reemplazar en la consulta.
     * @param string $columns [Opcional] Por defecto "*". Columnas.
     * @param int $limit [Opcional] Numero de datos a retornar.
     * @param string $orderBy [Opcional] Por defecto "ID DESC". Ordenar por.
     * @return Options
     */
    private static function select($where = '', $prepare = [], $columns = '*', $limit = '', $orderBy = 'ID DESC') {
        $db = DBController::getConnection();
        $table = Option::getTableName();
        $fetch = 'fetchAll';
        $select = $db->select($table, $fetch, $where, $prepare, $columns, $orderBy, $limit);
        $options = new Options();
        $options->addOptions($select);
        
        return $options;
    }

    /**
     * Metodo que obtiene todos las opciones.
     * @return array
     */
    public function getOptions() {
        return $this->options;
    }

    /**
     * Metodo que obtiene, segun su nombre asignado, una opción.
     * @param string $optionName
     * @return Option
     */
    public function getOption($optionName) {
        return $this->options[$optionName];
    }

    /**
     * Metodo que agrega una opción a la lista.
     * @param Option $option
     */
    public function addOption(Option $option) {
        $this->options[$option->getOptionName()] = $option;
    }

    /**
     * Metodo que obtiene un array con los datos de las opciones y los agrega a la lista.
     * @param array $option
     */
    public function addOptions($option) {
        foreach ($option as $value) {
            $this->addOption(new Option($value));
        }
    }

}
