<?php

/**
 * Modulo modelo: Gestiona grupos de Posts.
 */
namespace SoftnCMS\models\admin;

use SoftnCMS\controllers\DBController;
use SoftnCMS\models\admin\base\Models;

/**
 * Clase Posts para gestionar grupos de posts.
 * @author Nicolás Marulanda P.
 */
class Posts extends Models {
    
    /**
     * Constructor.
     */
    public function __construct() {
        parent::__construct(Post::getTableName(), __CLASS__);
    }
    
    /**
     * Método que obtiene todos los posts de la base de datos.
     * @return Posts|bool Si es FALSE, no hay datos.
     */
    public static function selectAll() {
        $select = self::select(Post::getTableName());
        
        return self::getInstanceData($select);
    }
    
    /**
     * Método que recibe un lista de datos y retorna un instancia.
     *
     * @param array $data Lista de datos.
     *
     * @return Posts|bool Si es FALSE, no hay datos.
     */
    public static function getInstanceData($data) {
        return parent::getInstance($data, __CLASS__);
    }
    
    /**
     * Método que obtiene un número limitado de datos.
     *
     * @param string $limit
     *
     * @return Posts|bool Si es FALSE, no hay datos.
     */
    public static function selectByLimit($limit) {
        $select = self::select(Post::getTableName(), '', [], '*', $limit);
        
        return self::getInstanceData($select);
    }
    
    /**
     * Método que obtiene todos los posts según el "ID" del un usuario.
     *
     * @param int $value Identificador del usuario.
     *
     * @return Posts|bool Si es FALSE, no hay datos.
     */
    public static function selectByUserID($value) {
        $select = self::selectBy(Post::getTableName(), $value, Post::USER_ID, \PDO::PARAM_INT);
        
        return self::getInstanceData($select);
    }
    
    /**
     * Método que obtiene todos los posts que coinciden su titulo.
     *
     * @param string $value Titulo.
     *
     * @return Posts|bool Si es FALSE, no hay datos.
     */
    public static function selectByTitle($value) {
        $val       = "%$value%";
        $parameter = ':' . Post::POST_TITLE;
        $where     = Post::POST_TITLE . " LIKE $parameter";
        $prepare[] = DBController::prepareStatement($parameter, $val, \PDO::PARAM_STR);
        
        $select = self::select(Post::getTableName(), $where, $prepare);
        
        return self::getInstanceData($select);
    }
    
    /**
     * Método que obtiene todos los posts según su estado.
     *
     * @param int $value Identificador del estado.
     *
     * @return Posts|bool Si es FALSE, no hay datos.
     */
    public static function selectByStatus($value) {
        $select = self::selectBy(Post::getTableName(), $value, Post::POST_STATUS, \PDO::PARAM_INT);
        
        return self::getInstanceData($select);
    }
    
    /**
     * Método que obtiene todos los posts según el estado de sus comentarios.
     *
     * @param int $value Identificador del estado de los comentarios.
     *
     * @return Posts|bool Si es FALSE, no hay datos.
     */
    public static function selectByCommentStatus($value) {
        $select = self::selectBy(Post::getTableName(), $value, Post::COMMENT_STATUS, \PDO::PARAM_INT);
        
        return self::getInstanceData($select);
    }
    
    /**
     * Método que obtiene todos los post según su número exacto de comentarios.
     *
     * @param int $value Número de comentarios.
     *
     * @return Posts|bool Si es FALSE, no hay datos.
     */
    public static function selectByComments($value) {
        $select = self::selectBy(Post::getTableName(), $value, Post::COMMENT_COUNT, \PDO::PARAM_INT);
        
        return self::getInstanceData($select);
    }
    
    /**
     * Método que obtiene todos los posts según su fecha de publicación.
     *
     * @param string $value Fecha.
     *
     * @return Posts|bool Si es FALSE, no hay datos.
     */
    public static function selectByDate($value) {
        $select = self::selectBy(Post::getTableName(), $value, Post::POST_DATE);
        
        return self::getInstanceData($select);
    }
    
    /**
     * Método que obtiene todos los posts según su fecha de actualización.
     *
     * @param string $value Fecha.
     *
     * @return Posts|bool Si es FALSE, no hay datos.
     */
    public static function selectByUpdate($value) {
        $select = self::selectBy(Post::getTableName(), $value, Post::POST_UPDATE);
        
        return self::getInstanceData($select);
    }
    
    /**
     * Método que obtiene el número total de datos.
     * @return int
     */
    public static function count() {
        return parent::countData(Post::getTableName());
    }
    
    /**
     * Método que recibe una lista de datos y los agrega a la lista actual.
     *
     * @param array $data Lista de datos.
     */
    public function addData($data) {
        foreach ($data as $value) {
            $this->add(new Post($value));
        }
    }
    
}
