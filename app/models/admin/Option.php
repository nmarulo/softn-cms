<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\controllers\DBController;

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
     * 
     * @param type $value
     * @return Option
     */
    public static function selectByName($value) {
        return self::selectBy($value, self::OPTION_NAME);
    }

    /**
     * 
     * @param type $value
     * @param type $column
     * @param type $dataType
     * @return Option
     */
    private static function selectBy($value, $column, $dataType = \PDO::PARAM_STR) {
        $parameter = ":$column";
        $where = "$column = $parameter";
        $prepare[] = DBController::prepareStatement($parameter, $value, $dataType);
        return self::select($where, $prepare);
    }

    /**
     * 
     * @param type $where
     * @param type $prepare
     * @param type $columns
     * @param type $limit
     * @param type $orderBy
     * @return Option
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
