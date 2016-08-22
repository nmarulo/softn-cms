<?php

/**
 * Modulo del modelo post-categoría.
 * Gestiona el borrado de relaciones post-categoría.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\controllers\DBController;
use SoftnCMS\models\admin\PostCategory;

/**
 * Clase que gestiona el borrado de relaciones post-categoría.
 *
 * @author Nicolás Marulanda P.
 */
class PostCategoryDelete {

    /** @var array Identificador de las categorías. */
    private $categoriesID;

    /** @var int Identificador del post. */
    private $postID;

    /** @var array Lista con los indices, valores y tipos de datos para la consulta. */
    private $prepareStatement;

    /**
     * Constructor.
     * @param array $categoriesID Identificador de las categorías.
     * @param int $postID Identificador del post.
     */
    public function __construct($categoriesID, $postID) {
        $this->prepareStatement = [];
        $this->categoriesID = $categoriesID;
        $this->postID = $postID;
    }

    /**
     * Metodo que borra los datos.
     * @return bool Si es TRUE, todo se realizo correctamente.
     */
    public function delete() {
        $db = DBController::getConnection();
        $table = PostCategory::getTableName();
        $parameterCategoryID = PostCategory::RELATIONSHIPS_CATEGORY_ID;
        $parameterPostID = PostCategory::RELATIONSHIPS_POST_ID;
        $where = "$parameterCategoryID = :$parameterCategoryID AND $parameterPostID = :$parameterPostID";
        $count = \count($this->categoriesID);
        $error = \FALSE;

        for ($i = 0; $i < $count && !$error; ++$i) {
            $categoryID = $this->categoriesID[$i];
            $this->prepareStatement = [];
            $this->prepare($categoryID);
            $error = !$db->delete($table, $where, $this->prepareStatement);
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
