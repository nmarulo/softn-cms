<?php

/**
 * Modulo del modelo post-etiqueta.
 * Gestiona grupos de relaciones post-etiqueta.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\controllers\DBController;
use SoftnCMS\models\admin\PostTerm;

/**
 * Clase que gestiona grupos de relaciones post-etiqueta.
 *
 * @author Nicolás Marulanda P.
 */
class PostsTerms {

    /**
     * Lista de etiquetas (ID), donde el indice o clave corresponde al ID 
     * del post.
     * @var array 
     */
    private $termsID;

    /**
     * Lista de posts (ID), donde el indice o clave corresponde al ID 
     * de la etiqueta.
     * @var array 
     */
    private $postsID;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->termsID = [];
        $this->postsID = [];
    }

    /**
     * Metodo que obtiene todos las relaciones post-etiqueta de la base de datos.
     * @return PostsTerms
     */
    public static function selectAll() {
        return self::select();
    }

    /**
     * Metodo que obtiene todas las etiquetas vinculadas a un post.
     * @param int $value Identificador del post.
     * @return array Lista con las etiquetas vinculadas.
     */
    public static function selectByPostID($value) {
        $select = self::selectBy($value, PostTerm::RELATIONSHIPS_POST_ID, \PDO::PARAM_INT);

        if ($select->hasTerms($value)) {
            return $select->getTerms($value);
        }

        return [];
    }

    /**
     * Metodo que obtiene todos los posts vinculados a una etiqueta,
     * @param int $value Identificador de la etiqueta.
     * @return array Lista con los posts vinculados.
     */
    public static function selectByTermID($value) {
        $select = self::selectBy($value, PostTerm::RELATIONSHIPS_TERM_ID, \PDO::PARAM_INT);

        if ($select->hasPosts($value)) {
            return $select->getPosts($value);
        }

        return [];
    }

    /**
     * Metodo que obtiene los datos segun las especificaciones dadas.
     * @param int|string $value Valor a buscar.
     * @param string $column Nombre de la columna en la tabla.
     * @param int $dataType [Opcional] Por defecto \PDO::PARAM_STR. Tipo de dato.
     * @return PostsTerms
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
     * @return PostsTerms
     */
    private static function select($where = '', $prepare = [], $columns = '*', $limit = '') {
        $db = DBController::getConnection();
        $table = PostTerm::getTableName();
        $fetch = 'fetchAll';
        $select = $db->select($table, $fetch, $where, $prepare, $columns, '', $limit);
        $postsTerms = new PostsTerms();
        $postsTerms->addPostsTerms($select);

        return $postsTerms;
    }

    /**
     * Metodo que comprueba si el identificador del post existe.
     * @param int $postID
     * @return bool 
     */
    public function hasTerms($postID) {
        return \array_key_exists($postID, $this->termsID);
    }

    /**
     * Metodo que comprueba si el identificador de la etiqueta existe.
     * @param int $termID
     * @return bool 
     */
    public function hasPosts($termID) {
        return \array_key_exists($termID, $this->postsID);
    }

    /**
     * Metodo que obtiene todos los post (ID) vinculados a una etiqueta.
     * @param int $id Identificador de la etiqueta.
     * @return array
     */
    public function getPosts($id) {
        return $this->postsID[$id];
    }

    /**
     * Metodo que obtiene todas las etiquetas (ID) vinculadas a un post.
     * @param int $id Identificador del post.
     * @return array
     */
    public function getTerms($id) {
        return $this->termsID[$id];
    }

    /**
     * Metodo que agrega las relaciones post-etiqueta.
     * @param PostTerm $postTerm
     */
    public function addPostTerm(PostTerm $postTerm) {
        $this->postsID[$postTerm->getTermID()][] = $postTerm->getPostID();
        $this->termsID[$postTerm->getPostID()][] = $postTerm->getTermID();
    }

    /**
     * Metodo que obtiene un array con los datos de la relación post-etiqueta y los agrega a la lista.
     * @param array $postTerm
     */
    public function addPostsTerms($postTerm) {
        foreach ($postTerm as $value) {
            $this->addPostTerm(new PostTerm($value));
        }
    }

}
