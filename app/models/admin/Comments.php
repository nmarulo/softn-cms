<?php

/**
 * Modulo del modelo de comentarios.
 * Gestiona grupos de comentarios.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\controllers\DBController;
use SoftnCMS\models\admin\Comment;
use SoftnCMS\models\admin\base\Models;

/**
 * Clase que gestiona grupos de comentarios.
 *
 * @author Nicolás Marulanda P.
 */
class Comments extends Models {

    /**
     * Constructor.
     */
    public function __construct() {
        parent::__construct(Comment::getTableName(), __CLASS__);
    }

    /**
     * Metodo que obtiene todos los comentarios de la base de datos.
     * @return Comments
     */
    public static function selectAll() {
        $select = self::select(Comment::getTableName());

        return self::getInstanceData($select);
    }

    /**
     * Metodo que obtiene todos los comentarios segun el nombre del autor.
     * @param string $value
     * @return Comments
     */
    public static function selectByAuthor($value) {
        $select = self::selectBy(Comment::getTableName(), $value, Comment::COMMENT_AUTHOR);

        return self::getInstanceData($select);
    }

    /**
     * Metodo que obtiene todos los comentarios segun el email del autor.
     * @param string $value
     * @return Comments
     */
    public static function selectByAuthorEmail($value) {
        $select = self::selectBy(Comment::getTableName(), $value, Comment::COMMENT_AUTHOR_EMAIL);

        return self::getInstanceData($select);
    }

    /**
     * Metodo que obtiene todos los comentarios segun su estado.
     * @param int $value
     * @return Comments
     */
    public static function selectByStatus($value) {
        $select = self::selectBy(Comment::getTableName(), $value, Comment::COMMENT_STATUS, \PDO::PARAM_INT);

        return self::getInstanceData($select);
    }

    /**
     * Metodo que obtiene todos los comentarios segun su fecha.
     * @param string $value
     * @return Comments
     */
    public static function selectByDate($value) {
        $select = self::selectBy(Comment::getTableName(), $value, Comment::COMMENT_DATE);

        return self::getInstanceData($select);
    }

    /**
     * Metodo que obtiene todos los comentarios segun el identificador del post.
     * @param int $value
     * @return Comments
     */
    public static function selectByPostID($value) {
        $select = self::selectBy(Comment::getTableName(), $value, Comment::POST_ID, \PDO::PARAM_INT);

        return self::getInstanceData($select);
    }

    /**
     * Metodo que recibe un lista de datos y retorna un instancia.
     * @param array $data Lista de datos.
     * @return Comments|bool Si es FALSE, no hay datos.
     */
    public static function getInstanceData($data) {
        return parent::getInstance($data, __CLASS__);
    }

    /**
     * Metodo que recibe una lista de datos y los agrega a la lista actual.
     * @param array $data Lista de datos.
     */
    public function addData($data) {
        foreach ($data as $value) {
            $this->add(new Comment($value));
        }
    }

}
