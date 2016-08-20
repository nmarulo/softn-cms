<?php

/**
 * Modulo del modelo de categorías.
 * Gestiona los datos de cada categoría.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\controllers\DBController;

/**
 * Clase que gestiona los datos de cada categoría.
 *
 * @author Nicolás Marulanda P.
 */
class Category {

    /** Identificador de la categoría. */
    const ID = 'ID';

    /** Nombre de la categoría. */
    const CATEGORY_NAME = 'category_name';

    /** Descripción de la categoría. */
    const CATEGORY_DESCRIPTION = 'category_description';

    /** Número de publicaciones vinculadas. */
    const CATEGORY_COUNT = 'category_count';

    /** @var string Nombre de la table. */
    private static $TABLE = \DB_PREFIX . 'categories';

    /** @var array Datos de la categoría. */
    private $category;

    /**
     * Constructor.
     * @param array $data
     */
    public function __construct($data) {
        $this->category = $data;
    }

    /**
     * Metodo que obtiene el nombre de la tabla.
     * @return string
     */
    public static function getTableName() {
        return self::$TABLE;
    }

    /**
     *  Metodo que retorna una instancia por defecto.
     * @return Category
     */
    public static function defaultInstance() {
        $data = [
            self::CATEGORY_NAME => '',
            self::CATEGORY_DESCRIPTION => '',
            self::CATEGORY_COUNT => 0,
        ];

        return new Category($data);
    }

    /**
     * Metodo que obtiene una categoría segun su "ID".
     * @param int $value
     * @return Category|bool
     */
    public static function selectByID($value) {
        return self::selectBy($value, self::ID, \PDO::PARAM_INT);
    }

    /**
     * Metodo que obtiene una categoría segun las especificaciones dadas.
     * @param int|string $value Valor a buscar.
     * @param string $column Nombre de la columna en la tabla.
     * @param int $dataType Tipo de dato.
     * @return Category|bool
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
     * @return Category|bool En caso de no obtener datos retorna FALSE.
     */
    private static function select($where = '', $prepare = [], $columns = '*', $limit = 1, $orderBy = 'ID DESC') {
        $db = DBController::getConnection();
        $table = self::$TABLE;
        $fetch = 'fetchAll';
        $select = $db->select($table, $fetch, $where, $prepare, $columns, $orderBy, $limit);

        if (empty($select)) {
            return \FALSE;
        }

        return new Category($select[0]);
    }

    /**
     * Metodo que obtiene el identificador de la categoría.
     * @return int
     */
    public function getID() {
        return $this->category[self::ID];
    }

    /**
     * Metodo que obtiene el nombre de la categoría.
     * @return string
     */
    public function getCategoryName() {
        return $this->category[self::CATEGORY_NAME];
    }

    /**
     * Metodo que obtiene la descripción de la categoría.
     * @return string
     */
    public function getCategoryDescription() {
        return $this->category[self::CATEGORY_DESCRIPTION];
    }

    /**
     * Metodo que obtiene el número de entradas vinculadas a la categoría.
     * @return int
     */
    public function getCategoryCount() {
        return $this->category[self::CATEGORY_COUNT];
    }

}
