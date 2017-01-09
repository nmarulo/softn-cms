<?php

/**
 * Modulo modelo: Gestiona el proceso de insertar relaciones post-categoría.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\controllers\DBController;
use SoftnCMS\models\admin\base\ModelInsert;

/**
 * Clase PostCategoryInsert para gestionar el proceso de insertar relaciones post-categoría.
 * @author Nicolás Marulanda P.
 */
class PostCategoryInsert extends ModelInsert {
    
    /** @var string Nombre de las columnas. */
    private static $COLUMNS = PostCategory::RELATIONSHIPS_CATEGORY_ID . ', ' . PostCategory::RELATIONSHIPS_POST_ID;
    
    /** @var string Nombre de los indices para preparar la consulta. */
    private static $VALUES = ':' . PostCategory::RELATIONSHIPS_CATEGORY_ID . ', ' . ':' . PostCategory::RELATIONSHIPS_POST_ID;
    
    /** @var array Identificadores de las categorías. */
    private $categoriesID;
    
    /** @var int Identificador del post. */
    private $postID;
    
    /** @var int Identificador de la categoría. */
    private $categoryID;
    
    /**
     * Constructor,
     *
     * @param array $categoriesID Identificadores de las categorías.
     * @param int   $postID       Identificador del post.
     */
    public function __construct($categoriesID, $postID) {
        parent::__construct(PostCategory::getTableName(), self::$COLUMNS, self::$VALUES);
        $this->categoriesID = $categoriesID;
        $this->postID       = $postID;
        $this->categoryID   = 0;
    }
    
    /**
     * Método que inserta los datos.
     * @return bool Si es TRUE, se realizo correctamente.
     */
    public function insert() {
        $db    = DBController::getConnection();
        $table = PostCategory::getTableName();
        $count = \count($this->categoriesID);
        $error = \FALSE;
        
        for ($i = 0; $i < $count && !$error; ++$i) {
            $this->categoryID = $this->categoriesID[$i];
            $this->prepare();
            $error                  = !$db->insert($table, self::$COLUMNS, self::$VALUES, $this->prepareStatement);
            $this->prepareStatement = [];
        }
        
        return !$error;
    }
    
    /**
     * Método que establece los datos a preparar.
     */
    protected function prepare() {
        $this->addPrepare(':' . PostCategory::RELATIONSHIPS_CATEGORY_ID, $this->categoryID, \PDO::PARAM_INT);
        $this->addPrepare(':' . PostCategory::RELATIONSHIPS_POST_ID, $this->postID, \PDO::PARAM_INT);
    }
    
}
