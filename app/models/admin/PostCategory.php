<?php

/**
 * Modulo modelo: Gestiona los datos de cada relación post-categoría.
 */
namespace SoftnCMS\models\admin;

/**
 * Clase PostCategory para gestionar los datos de cada relación post-categoría.
 * @author Nicolás Marulanda P.
 */
class PostCategory {
    
    /** Identificador de la entrada. */
    const RELATIONSHIPS_POST_ID = 'relationships_post_ID';
    
    /** Identificador de la categoría. */
    const RELATIONSHIPS_CATEGORY_ID = 'relationships_category_ID';
    
    /** @var string Nombre de la table. */
    private static $TABLE = \DB_PREFIX . 'posts_categories';
    
    /** @var array Datos. */
    private $postCategory;
    
    /**
     * Constructor.
     *
     * @param array $data
     */
    public function __construct($data) {
        $this->postCategory = $data;
    }
    
    /**
     * Método que obtiene el nombre de la tabla.
     * @return string
     */
    public static function getTableName() {
        return self::$TABLE;
    }
    
    /**
     * Método que obtiene el identificador del post.
     * @return int
     */
    public function getPostID() {
        return $this->postCategory[self::RELATIONSHIPS_POST_ID];
    }
    
    /**
     * Método que obtiene el identificador de la categoría.
     * @return int
     */
    public function getCategoryID() {
        return $this->postCategory[self::RELATIONSHIPS_CATEGORY_ID];
    }
    
}
