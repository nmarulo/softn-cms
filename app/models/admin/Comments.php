<?php

/**
 * Modulo modelo: Gestiona grupos de comentarios.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\models\admin\base\Models;

/**
 * Clase Comments para gestionar grupos de comentarios.
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
     * Método que obtiene todos los comentarios de la base de datos.
     * @return Comments|bool Si es FALSE, no hay datos.
     */
    public static function selectAll() {
        $select = self::select(Comment::getTableName());
        
        return self::getInstanceData($select);
    }
    
    /**
     * Método que recibe un lista de datos y retorna un instancia.
     *
     * @param array $data Lista de datos.
     *
     * @return Comments|bool Si es FALSE, no hay datos.
     */
    public static function getInstanceData($data) {
        return parent::getInstance($data, __CLASS__);
    }
    
    /**
     * Método que obtiene un número limitado de datos.
     *
     * @param string $limit
     *
     * @return Comments|bool Si es FALSE, no hay datos.
     */
    public static function selectByLimit($limit) {
        $select = self::select(Comment::getTableName(), '', [], '*', $limit);
        
        return self::getInstanceData($select);
    }
    
    /**
     * Método que obtiene todos los comentarios según el nombre del autor.
     *
     * @param string $value
     *
     * @return Comments|bool Si es FALSE, no hay datos.
     */
    public static function selectByAuthor($value) {
        $select = self::selectBy(Comment::getTableName(), $value, Comment::COMMENT_AUTHOR);
        
        return self::getInstanceData($select);
    }
    
    /**
     * Método que obtiene todos los comentarios según el email del autor.
     *
     * @param string $value
     *
     * @return Comments|bool Si es FALSE, no hay datos.
     */
    public static function selectByAuthorEmail($value) {
        $select = self::selectBy(Comment::getTableName(), $value, Comment::COMMENT_AUTHOR_EMAIL);
        
        return self::getInstanceData($select);
    }
    
    /**
     * Método que obtiene todos los comentarios según su estado.
     *
     * @param int $value
     *
     * @return Comments|bool Si es FALSE, no hay datos.
     */
    public static function selectByStatus($value) {
        $select = self::selectBy(Comment::getTableName(), $value, Comment::COMMENT_STATUS, \PDO::PARAM_INT);
        
        return self::getInstanceData($select);
    }
    
    /**
     * Método que obtiene todos los comentarios según su fecha.
     *
     * @param string $value
     *
     * @return Comments|bool Si es FALSE, no hay datos.
     */
    public static function selectByDate($value) {
        $select = self::selectBy(Comment::getTableName(), $value, Comment::COMMENT_DATE);
        
        return self::getInstanceData($select);
    }
    
    /**
     * Método que obtiene todos los comentarios según el identificador del post.
     *
     * @param int $value
     *
     * @return Comments|bool Si es FALSE, no hay datos.
     */
    public static function selectByPostID($value) {
        $select = self::selectBy(Comment::getTableName(), $value, Comment::POST_ID, \PDO::PARAM_INT);
        
        return self::getInstanceData($select);
    }
    
    /**
     * Método que obtiene el número total de datos.
     * @return int
     */
    public static function count() {
        return parent::countData(Comment::getTableName());
    }
    
    /**
     * Método que recibe una lista de datos y los agrega a la lista actual.
     *
     * @param array $data Lista de datos.
     */
    public function addData($data) {
        foreach ($data as $value) {
            $this->add(new Comment($value));
        }
    }
    
}
