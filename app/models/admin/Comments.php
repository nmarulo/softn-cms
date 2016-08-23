<?php

/**
 * Modulo del modelo de comentarios.
 * Gestiona grupos de comentarios.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\models\admin\Comment;
use SoftnCMS\controllers\DBController;

/**
 * Clase que gestiona grupos de comentarios.
 *
 * @author Nicolás Marulanda P.
 */
class Comments {

    /**
     * Lista, donde el indice o clave corresponde al ID.
     * @var array 
     */
    private $comments;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->comments = [];
    }

    /**
     * Metodo que obtiene todos los comentarios de la base de datos.
     * @return Comments
     */
    public static function selectAll() {
        return self::select();
    }

    /**
     * Metodo que obtiene todos los comentarios segun el nombre del autor.
     * @param string $value
     * @return Comments
     */
    public static function selectByAuthor($value) {
        return self::selectBy($value, Comment::COMMENT_AUTHOR);
    }

    /**
     * Metodo que obtiene todos los comentarios segun el email del autor.
     * @param string $value
     * @return Comments
     */
    public static function selectByAuthorEmail($value) {
        return self::selectBy($value, Comment::COMMENT_AUTHOR_EMAIL);
    }

    /**
     * Metodo que obtiene todos los comentarios segun su estado.
     * @param int $value
     * @return Comments
     */
    public static function selectByStatus($value) {
        return self::selectBy($value, Comment::COMMENT_STATUS, \PDO::PARAM_INT);
    }

    /**
     * Metodo que obtiene todos los comentarios segun su fecha.
     * @param string $value
     * @return Comments
     */
    public static function selectByDate($value) {
        return self::selectBy($value, Comment::COMMENT_DATE);
    }

    /**
     * Metodo que obtiene todos los comentarios segun el identificador del post.
     * @param int $value
     * @return Comments
     */
    public static function selectByPostID($value) {
        return self::selectBy($value, Comment::POST_ID, \PDO::PARAM_INT);
    }

    /**
     * Metodo que obtiene los comentarios segun las especificaciones dadas.
     * @param int|string $value Valor a buscar.
     * @param string $column Nombre de la columna en la tabla.
     * @param int $dataType [Opcional] Por defecto \PDO::PARAM_STR. Tipo de dato.
     * @return Comments
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
     * @return Comments
     */
    private static function select($where = '', $prepare = [], $columns = '*', $limit = '', $orderBy = 'ID DESC') {
        $db = DBController::getConnection();
        $table = Comment::getTableName();
        $fetch = 'fetchAll';
        $select = $db->select($table, $fetch, $where, $prepare, $columns, $orderBy, $limit);
        $comments = new Comments();
        $comments->addComments($select);

        return $comments;
    }

    /**
     * Metodo que obtiene todos los comentarios.
     * @return array
     */
    public function getComments() {
        return $this->comments;
    }

    /**
     * Metodo que obtiene, segun su ID, un comentario.
     * @param int $id
     * @return Comment
     */
    public function getComment($id) {
        return $this->comments[$id];
    }

    /**
     * Metodo que agrega un comentario a la lista.
     * @param Comment $comment
     */
    public function addComment(Comment $comment) {
        $this->comments[$comment->getID()] = $comment;
    }

    /**
     * Metodo que obtiene un array con los datos de los comentarios y los agrega a la lista.
     * @param array $comment
     */
    public function addComments($comment) {
        foreach ($comment as $value) {
            $this->addComment(new Comment($value));
        }
    }

    /**
     * Metodo que obtiene los ultimos comentarios.
     * @param int $limit Numero de comentarios.
     * @return array
     */
    public function lastComments($limit) {
        $output = [];

        if (empty($this->comments)) {
            $select = self::select('', [], '*', $limit);
            $output = $select->getComments();
        } else {
            $output = \array_slice($this->getComments(), 0, $limit, \TRUE);
        }

        return $output;
    }

    /**
     * Metodo que obtiene el número total de comentarios.
     * @return int
     */
    public function count() {
        $db = DBController::getConnection();
        $table = Comment::getTableName();
        $fetch = 'fetchAll';
        $columns = 'COUNT(*) AS count';
        $select = $db->select($table, $fetch, '', [], $columns);

        return $select[0]['count'];
    }

}
