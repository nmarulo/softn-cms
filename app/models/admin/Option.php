<?php

/**
 * Modulo del modelo de las opciones configurables de la aplicación.
 * Gestiona los datos de cada opción.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\controllers\DBController;

/**
 * Clase que gestiona los datos de cada opción.
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
     * Metodo que obtiene una opción segun su nombre asignado.
     * @param string $value
     * @return Option
     */
    public static function selectByName($value) {
        return self::selectBy($value, self::OPTION_NAME);
    }

    /**
     * Metodo que obtiene una opción segun las especificaciones dadas.
     * @param int|string $value Valor a buscar.
     * @param string $column Nombre de la columna en la tabla.
     * @param int $dataType Tipo de dato.
     * @return Option|bool
     */
    private static function selectBy($value, $column, $dataType = \PDO::PARAM_STR) {
        $parameter = ":$column";
        $where = "$column = $parameter";
        $prepare[] = DBController::prepareStatement($parameter, $value, $dataType);
        return self::select($where, $prepare);
    }

    /**
     * Metodo que realiza una consulta a la base de datos.
     * @param string $where [Opcional] Condiciones.
     * @param array $prepare [Opcional] Lista de indices a reemplazar en la consulta.
     * @param string $columns [Opcional] Por defecto "*". Columnas.
     * @param int $limit [Opcional] Por defecto 1. Numero de datos a retornar.
     * @param string $orderBy [Opcional] Por defecto "ID DESC". Ordenar por.
     * @return Option|bool En caso de no obtener datos retorna FALSE.
     */
    private static function select($where = '', $prepare = [], $columns = '*', $limit = 1, $orderBy = 'ID DESC') {
        $db = DBController::getConnection();
        $table = self::$TABLE;
        $fetch = 'fetchAll';
        $select = $db->select($table, $fetch, $where, $prepare, $columns, $orderBy, $limit);

        if (empty($select)) {
            return \FALSE;
        }

        return new Option($select[0]);
    }

    /**
     * Metodo que obtiene el nombre de la tabla.
     * @return string
     */
    public static function getTableName() {
        return self::$TABLE;
    }

    /**
     * Metodo que obtiene el identificador.
     * @return int
     */
    public function getID() {
        return $this->option[Option::ID];
    }

    /**
     * Metodo que obtiene el nombre asignado.
     * @return string
     */
    public function getOptionName() {
        return $this->option[Option::OPTION_NAME];
    }

    /**
     * Metodo que obtiene el valor.
     * @return string
     */
    public function getOptionValue() {
        return $this->option[Option::OPTION_VALUE];
    }

}
