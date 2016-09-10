<?php

/**
 * Modulo del modelo post-categoría.
 * Gestiona grupos de relaciones post-categoría.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\controllers\DBController;
use SoftnCMS\models\admin\PostCategory;
use SoftnCMS\models\admin\base\BaseModels;

/**
 * Clase que gestiona grupos de relaciones post-categoría.
 *
 * @author Nicolás Marulanda P.
 */
class PostsCategories extends BaseModels {

    /**
     * Lista de identificadores(ID) de categorías, donde el indice o clave corresponde al ID 
     * del post.
     * @var array 
     */
    private $categoriesID;

    /**
     * Lista de identificadores(ID) de posts, donde el indice o clave corresponde al ID de la categoría.
     * @var array 
     */
    private $postsID;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->categoriesID = [];
        $this->postsID = [];
    }

    /**
     * Metodo que obtiene todos las relaciones post-categoría de la base de datos.
     * @return PostsCategories|bool Si es False, no hay datos.
     */
    public static function selectAll() {
        $select = self::select(PostCategory::getTableName(), '', [], '*', '', '');

        return self::getInstanceData($select);
    }

    /**
     * Metodo que obtiene todos los identificadores(ID) de las categorías vinculadas a un post.
     * @param int $value Identificador del post.
     * @return array|bool Si es False, no hay datos.
     */
    public static function selectByPostID($value) {
        $select = self::selectBy(PostCategory::getTableName(), $value, PostCategory::RELATIONSHIPS_POST_ID, \PDO::PARAM_INT);

        $output = self::getInstanceData($select);

        if ($output === \FALSE) {
            return \FALSE;
        }

        return $output->getCategories($value);
    }

    /**
     * Metodo que obtiene todos los identificadores(ID) de los posts vinculados a un categoría.
     * @param int $value Identificador de la categoría.
     * @return array|bool Si es False, no hay datos.
     */
    public static function selectByCategoryID($value) {
        $select = self::selectBy(PostCategory::getTableName(), $value, PostCategory::RELATIONSHIPS_CATEGORY_ID, \PDO::PARAM_INT);

        $output = self::getInstanceData($select);

        if ($output === \FALSE) {
            return \FALSE;
        }

        return $output->getPosts($value);
    }

    /**
     * Metodo que obtiene un objeto segun las especificaciones dadas.
     * @param string $table Nombre de la tabla.
     * @param int|string $value Valor a buscar.
     * @param string $column Nombre de la columna en la tabla.
     * @param int $dataType Tipo de dato.
     * @return array|bool Si es FALSE, no hay datos.
     */
    protected static function selectBy($table, $value, $column, $dataType = \PDO::PARAM_STR) {
        $parameter = ":$column";
        $where = "$column = $parameter";
        $prepare[] = DBController::prepareStatement($parameter, $value, $dataType);

        return self::select($table, $where, $prepare, '*', '', '');
    }

    /**
     * Metodo que recibe un lista de datos y retorna un instancia.
     * @param array $data Lista de datos.
     * @return PostsCategories|bool Si es FALSE, no hay datos.
     */
    public static function getInstanceData($data) {
        if ($data === \FALSE) {
            return \FALSE;
        }

        $output = new PostsCategories();
        $output->addData($data);

        return $output;
    }

    /**
     * Metodo que obtiene todos identificadores(ID) de los post vinculados a una categoría.
     * @param int $id Identificador de la categoría.
     * @return array
     */
    public function getPosts($id) {
        return $this->postsID[$id];
    }

    /**
     * Metodo que obtiene todos los identificadores(ID) de las categorías vinculadas a un post.
     * @param int $id Identificador del post.
     * @return array
     */
    public function getCategories($id) {
        return $this->categoriesID[$id];
    }

    /**
     * Metodo que agrega las relaciones post-categoría.
     * @param PostCategory $postCategory
     */
    public function add(PostCategory $postCategory) {
        $this->postsID[$postCategory->getCategoryID()][] = $postCategory->getPostID();
        $this->categoriesID[$postCategory->getPostID()][] = $postCategory->getCategoryID();
    }

    /**
     * Metodo que obtiene un array con los datos de la relación post-categoría y los agrega a la lista.
     * @param array $postCategory
     */
    public function addData($postCategory) {
        foreach ($postCategory as $value) {
            $this->add(new PostCategory($value));
        }
    }

}
