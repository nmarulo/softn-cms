<?php

/**
 * Modulo del modelo de etiquetas.
 * Gestiona los datos de cada etiqueta.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\controllers\DBController;

/**
 * Clase que gestiona los datos de cada etiqueta.
 *
 * @author Nicolás Marulanda P.
 */
class Term {

    /** Identificador de la etiqueta. */
    const ID = 'ID';

    /** Nombre de la etiqueta. */
    const TERM_NAME = 'term_name';

    /** Descripción de la etiqueta. */
    const TERM_DESCRIPTION = 'term_description';

    /** Número de publicaciones vinculadas. */
    const TERM_COUNT = 'term_count';

    /** @var string Nombre de la table. */
    private static $TABLE = \DB_PREFIX . 'terms';

    /** @var array Datos de la etiqueta. */
    private $term;

    /**
     * Constructor.
     * @param array $data
     */
    public function __construct($data) {
        $this->term = $data;
    }

    /**
     * Metodo que obtiene el nombre de la tabla.
     * @return string
     */
    public static function getTableName() {
        return self::$TABLE;
    }

    /**
     * Metodo que retorna una instancia por defecto.
     * @return Term
     */
    public static function defaultInstance() {
        $data = [
            self::TERM_NAME => '',
            self::TERM_DESCRIPTION => '',
            self::TERM_COUNT => 0,
        ];

        return new Term($data);
    }

    /**
     * Metodo que obtiene una etiqueta segun su "ID".
     * @param int $value
     * @return Term|bool
     */
    public static function selectByID($value) {
        return self::selectBy($value, self::ID, \PDO::PARAM_INT);
    }

    /**
     * Metodo que obtiene una etiqueta segun las especificaciones dadas.
     * @param int|string $value Valor a buscar.
     * @param string $column Nombre de la columna en la tabla.
     * @param int $dataType Tipo de dato.
     * @return Term|bool
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
     * @return Term|bool En caso de no obtener datos retorna FALSE.
     */
    private static function select($where = '', $prepare = [], $columns = '*', $limit = 1, $orderBy = 'ID DESC') {
        $db = DBController::getConnection();
        $table = self::$TABLE;
        $fetch = 'fetchAll';
        $select = $db->select($table, $fetch, $where, $prepare, $columns, $orderBy, $limit);

        if (empty($select)) {
            return \FALSE;
        }

        return new Term($select[0]);
    }

    /**
     * Metodo que obtiene el identificador de la etiqueta.
     * @return int
     */
    public function getID() {
        return $this->term[self::ID];
    }

    /**
     * Metodo que obtiene el nombre de la etiqueta.
     * @return string
     */
    public function getTermName() {
        return $this->term[self::TERM_NAME];
    }

    /**
     * Metodo que obtiene la descripción de la etiqueta.
     * @return string
     */
    public function getTermDescription() {
        return $this->term[self::TERM_DESCRIPTION];
    }

    /**
     * Metodo que obtiene el número de entradas vinculadas a la etiqueta.
     * @return int
     */
    public function getTermCount() {
        return $this->term[self::TERM_COUNT];
    }

}
