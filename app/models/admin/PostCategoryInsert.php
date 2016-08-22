<?php

/**
 * Modulo del modelo post-categoría.
 * Gestiona el proceso de insertar relaciones post-categoría.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\controllers\DBController;
use SoftnCMS\models\admin\PostCategory;

/**
 * Clase que gestiona el proceso de insertar relaciones post-categoría.
 *
 * @author Nicolás Marulanda P.
 */
class PostCategoryInsert {

    /** @var string Nombre de las columnas. */
    private static $COLUMNS = PostCategory::RELATIONSHIPS_CATEGORY_ID . ', ' . PostCategory::RELATIONSHIPS_POST_ID;

    /** @var string Nombre de los indices para preparar la consulta. */
    private static $VALUES = ':' . PostCategory::RELATIONSHIPS_CATEGORY_ID . ', ' . ':' . PostCategory::RELATIONSHIPS_POST_ID;

    /** @var array Lista con los indices, valores y tipos de datos para la consulta. */
    private $prepareStatement;

    /** @var array Identificadores de las categorías. */
    private $categoriesID;

    /** @var int Identificador del post. */
    private $postID;

    /**
     * Constructor,
     * @param array $categoriesID Identificadores de las categorías.
     * @param int $postID Identificador del post.
     */
    public function __construct($categoriesID, $postID) {
        $this->prepareStatement = [];
        $this->categoriesID = $categoriesID;
        $this->postID = $postID;
    }
    
    /**
     * Metodo que realiza el proceso de insertar el post en la base de datos.
     * @return bool Si es TRUE, todo se realizo correctamente.
     */
    public function insert() {
        $db = DBController::getConnection();
        $table = PostCategory::getTableName();
        $count = \count($this->categoriesID);
        $error = \FALSE;
        
        for($i = 0; $i < $count && !$error; ++$i){
            $categoryID = $this->categoriesID[$i];
            $this->prepareStatement = [];
            $this->prepare($categoryID);
            $error = !$db->insert($table, self::$COLUMNS, self::$VALUES, $this->prepareStatement);
        }

        return !$error;
    }


    /**
     * Metodo que establece los datos a preparar.
     * @param int $categoryID Identificador de la categoría.
     */
    private function prepare($categoryID) {
        $this->addPrepare(':' . PostCategory::RELATIONSHIPS_CATEGORY_ID, $categoryID, \PDO::PARAM_INT);
        $this->addPrepare(':' . PostCategory::RELATIONSHIPS_POST_ID, $this->postID, \PDO::PARAM_INT);
    }

    /**
     * Metodo que guarda los datos establecidos.
     * @param string $parameter Indice a buscar. EJ: ":ID"
     * @param string $value Valor del indice.
     * @param int $dataType Tipo de dato. EJ: \PDO::PARAM_*
     */
    private function addPrepare($parameter, $value, $dataType) {
        $this->prepareStatement[] = DBController::prepareStatement($parameter, $value, $dataType);
    }

}
