<?php

/**
 * Modulo del modelo post.
 * Gestiona grupos de Posts.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\models\admin\Post;
use SoftnCMS\controllers\DBController;

/**
 * Clase que gestiona grupos de posts.
 *
 * @author Nicolás Marulanda P.
 */
class Posts {

    /**
     * Lista, donde el indice o clave corresponde al ID.
     * @var array 
     */
    private $posts;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->posts = [];
    }

    /**
     * Metodo que obtiene todos los posts de la base de datos.
     * @return Posts
     */
    public static function selectAll() {
        return self::select();
    }

    /**
     * Metodo que obtiene todos los post segun el "ID" del un usuario.
     * @param int $value
     * @return Posts
     */
    public static function selectByUserID($value) {
        return self::selectBy($value, Post::USER_ID, \PDO::PARAM_INT);
    }

    /**
     * Metodo que obtiene todos los post que coinciden su titulo.
     * @param string $val
     * @return Posts
     */
    public static function selectByTitle($val) {
        $value = "%$val%";
        $parameter = ':' . Post::POST_TITLE;
        $where = Post::POST_TITLE . " LIKE $parameter";
        $prepare[] = DBController::prepareStatement($parameter, $value, \PDO::PARAM_STR);
        return self::select($where, $prepare);
    }

    /**
     * Metodo que obtiene todos los post segun su estatus.
     * @param int $value
     * @return Posts
     */
    public static function selectByStatus($value) {
        return self::selectBy($value, Post::POST_STATUS, \PDO::PARAM_INT);
    }

    /**
     * Metodo que obtiene todos los post segun el estado de sus comentarios.
     * @param int $value
     * @return Posts
     */
    public static function selectByCommentStatus($value) {
        return self::selectBy($value, Post::COMMENT_STATUS, \PDO::PARAM_INT);
    }

    /**
     * Metodo que obtiene todos los post segun su número de comentarios.
     * @param int $value
     * @return Posts
     */
    public static function selectByComments($value) {
        return self::selectBy($value, Post::COMMENT_COUNT, \PDO::PARAM_INT);
    }

    /**
     * Metodo que obtiene todos los post segun su fecha de publicación.
     * @param string $value
     * @return Posts
     */
    public static function selectByDate($value) {
        return self::selectBy($value, Post::POST_DATE);
    }

    /**
     * Metodo que obtiene todos los post segun su fecha de actualización.
     * @param string $value
     * @return Posts
     */
    public static function selectByUpdate($value) {
        return self::selectBy($value, Post::POST_UPDATE);
    }

    /**
     * Metodo que obtiene los posts segun las especificaciones dadas.
     * @param int|string $value Valor a buscar.
     * @param string $column Nombre de la columna en la tabla.
     * @param int $dataType [Opcional] Por defecto \PDO::PARAM_STR. Tipo de dato.
     * @return Posts
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
     * @return Posts
     */
    private static function select($where = '', $prepare = [], $columns = '*', $limit = '', $orderBy = 'ID DESC') {
        $db = DBController::getConnection();
        $table = Post::getTableName();
        $fetch = 'fetchAll';
        $select = $db->select($table, $fetch, $where, $prepare, $columns, $orderBy, $limit);
        $posts = new Posts();
        $posts->addPosts($select);
        
        return $posts;
    }

    /**
     * Metodo que obtiene todos los posts.
     * @return array
     */
    public function getPosts() {
        return $this->posts;
    }

    /**
     * Metodo que obtiene, segun su ID, un post.
     * @param int $id
     * @return Post
     */
    public function getPost($id) {
        return $this->posts[$id];
    }

    /**
     * Metodo que agrega un post a la lista.
     * @param Post $post
     */
    public function addPost(Post $post) {
        $this->posts[$post->getID()] = $post;
    }

    /**
     * Metodo que obtiene un array con los datos de los post y los agrega a la lista.
     * @param array $post
     */
    public function addPosts($post) {
        foreach ($post as $value) {
            $this->addPost(new Post($value));
        }
    }

    /**
     * Metodo que obtiene los ultimos post.
     * @param int $limit Numero de post.
     * @return array
     */
    public function lastPosts($limit) {
        $output = [];

        if (empty($this->posts)) {
            $select = self::select('', [], '*', $limit);
            $output = $select->getPosts();
        } else {
            $output = \array_slice($this->getPosts(), 0, $limit, \TRUE);
        }
        
        return $output;
    }

    /**
     * Metodo que obtiene el número total de POSTS.
     * @return int
     */
    public function count() {
        $db = DBController::getConnection();
        $table = Post::getTableName();
        $fetch = 'fetchAll';
        $columns = 'COUNT(*) AS count';
        $select = $db->select($table, $fetch, '', [], $columns);
        
        return $select[0]['count'];
    }

}
