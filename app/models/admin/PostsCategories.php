<?php

/**
 * Modulo del modelo post-categoría.
 * Gestiona grupos de relaciones post-categoría.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\controllers\DBController;
use SoftnCMS\models\admin\PostCategory;
use SoftnCMS\models\admin\Categories;

/**
 * Clase que gestiona grupos de relaciones post-categoría.
 *
 * @author Nicolás Marulanda P.
 */
class PostsCategories {

    /**
     * Lista, donde el indice o clave corresponde al ID de la categoría.
     * @var array 
     */
    private $categories;

    /**
     * Lista, donde el indice o clave corresponde al ID del post.
     * @var array 
     */
    private $posts;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->categories = [];
        $this->posts = [];
    }

    /**
     * Metodo que obtiene todos las relaciones post-categoría de la base de datos.
     * @return PostsCategories
     */
    public static function selectAll() {
        return self::select();
    }

    /**
     * Metodo que obtiene todas las relaciones post-categoría, segun su ID, de un post.
     * @param int $value
     * @return array Lista con las categorías vinculadas.
     */
    public static function selectByPostID($value) {
        $select = self::selectBy($value, PostCategory::RELATIONSHIPS_POST_ID, \PDO::PARAM_INT);

        if ($select->hasCategories($value)) {
            return $select->getPost($value);
        }

        return [];
    }

    /**
     * Metodo que obtiene todas las relaciones post-categoría, segun su ID, de un post.
     * @param int $value
     * @return array Lista con los post vinculados.
     */
    public static function selectByCategoryID($value) {
        $select = self::selectBy($value, PostCategory::RELATIONSHIPS_CATEGORY_ID, \PDO::PARAM_INT);

        if ($select->hasPosts($value)) {
            return $select->getCategory($value);
        }

        return [];
    }

    /**
     * Metodo que obtiene los datos segun las especificaciones dadas.
     * @param int|string $value Valor a buscar.
     * @param string $column Nombre de la columna en la tabla.
     * @param int $dataType [Opcional] Por defecto \PDO::PARAM_STR. Tipo de dato.
     * @return PostsCategories
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
     * @return PostsCategories
     */
    private static function select($where = '', $prepare = [], $columns = '*', $limit = '') {
        $db = DBController::getConnection();
        $table = PostCategory::getTableName();
        $fetch = 'fetchAll';
        $select = $db->select($table, $fetch, $where, $prepare, $columns, '', $limit);
        $postsCategories = new PostsCategories();
        $postsCategories->addPostsCategories($select);

        return $postsCategories;
    }

    /**
     * Metodo que comprueba si el identificador del post existe.
     * @param int $postID
     * @return bool 
     */
    public function hasCategories($postID) {
        return \array_key_exists($postID, $this->posts);
    }

    /**
     * Metodo que comprueba si el identificador de la categoría existe.
     * @param int $categoryID
     * @return bool 
     */
    public function hasPosts($categoryID) {
        return \array_key_exists($categoryID, $this->categories);
    }

    /**
     * Metodo que obtiene todas las relaciones post-categoría, segun su ID, de un post.
     * @param int $id
     * @return array
     */
    public function getPost($id) {
        return $this->posts[$id];
    }

    /**
     * Metodo que obtiene todas las relaciones post-categoría, segun su ID, de una categoría.
     * @param int $id
     * @return array
     */
    public function getCategory($id) {
        return $this->categories[$id];
    }

    /**
     * Metodo que agrega las relaciones post-categoría.
     * @param PostCategory $postCategory
     */
    public function addPostCategory(PostCategory $postCategory) {
        $this->posts[$postCategory->getPostID()][] = $postCategory->getCategoryID();
        $this->categories[$postCategory->getCategoryID()][] = $postCategory->getPostID();
    }

    /**
     * Metodo que obtiene un array con los datos de la relación post-categoría y los agrega a la lista.
     * @param array $postCategory
     */
    public function addPostsCategories($postCategory) {
        foreach ($postCategory as $value) {
            $this->addPostCategory(new PostCategory($value));
        }
    }

}
