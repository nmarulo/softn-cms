<?php

/**
 * Modulo del modelo de categorías.
 * Gestiona grupos de categorías.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\controllers\DBController;
use SoftnCMS\models\admin\Category;

/**
 * Clase que gestiona grupos de categorías.
 *
 * @author Nicolás Marulanda P.
 */
class Categories {

    /**
     * Lista, donde el indice o clave corresponde al ID.
     * @var array 
     */
    private $categories;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->categories = [];
    }
    
    /**
     * Metodo que obtiene todas las categorías de la base de datos.
     * @return Categories
     */
    public static function selectAll() {
        return self::select();
    }
    
    /**
     * Metodo que obtiene los categorías segun las especificaciones dadas.
     * @param int|string $value Valor a buscar.
     * @param string $column Nombre de la columna en la tabla.
     * @param int $dataType [Opcional] Por defecto \PDO::PARAM_STR. Tipo de dato.
     * @return Categories
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
     * @param int $limit [Opcional] Numero de datos a retornar.
     * @param string $orderBy [Opcional] Por defecto "ID DESC". Ordenar por.
     * @return Categories
     */
    private static function select($where = '', $prepare = [], $columns = '*', $limit = '', $orderBy = 'ID DESC') {
        $db = DBController::getConnection();
        $table = Category::getTableName();
        $fetch = 'fetchAll';
        $select = $db->select($table, $fetch, $where, $prepare, $columns, $orderBy, $limit);
        $categories = new Categories();
        $categories->addCategories($select);

        return $categories;
    }
    
    /**
     * Metodo que obtiene todas las categorías.
     * @return array
     */
    public function getCategories() {
        return $this->categories;
    }

    /**
     * Metodo que obtiene, segun su ID, una categoría.
     * @param int $id
     * @return Category
     */
    public function getCategory($id) {
        return $this->categories[$id];
    }

    /**
     * Metodo que agrega una categoría a la lista.
     * @param Category $category
     */
    public function addCategory(Category $category) {
        $this->categories[$category->getID()] = $category;
    }

    /**
     * Metodo que obtiene un array con los datos de las categorías y los agrega a la lista.
     * @param array $category
     */
    public function addCategories($category) {
        foreach ($category as $value) {
            $this->addCategory(new Category($value));
        }
    }
    
    /**
     * Metodo que obtiene el número total de categorías.
     * @return int
     */
    public function count() {
        $db = DBController::getConnection();
        $table = Category::getTableName();
        $fetch = 'fetchAll';
        $columns = 'COUNT(*) AS count';
        $select = $db->select($table, $fetch, '', [], $columns);

        return $select[0]['count'];
    }

}
