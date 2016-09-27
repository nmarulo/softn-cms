<?php

/**
 * Modulo del modelo post-categoría.
 * Gestiona el borrado de relaciones post-categoría.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\controllers\DBController;
use SoftnCMS\models\admin\PostCategory;
use SoftnCMS\models\admin\base\ModelDelete;

/**
 * Clase que gestiona el borrado de relaciones post-categoría.
 *
 * @author Nicolás Marulanda P.
 */
class PostCategoryDelete extends ModelDelete {

    /** @var array Identificador de las categorías. */
    private $categoriesID;

    /** @var int Identificador del post. */
    private $postID;

    /** @var int Identificador de la categoría. */
    private $categoryID;

    /**
     * Constructor.
     * @param array $categoriesID Identificador de las categorías.
     * @param int $postID Identificador del post.
     */
    public function __construct($categoriesID, $postID) {
        parent::__construct(0, PostCategory::getTableName());
        $this->categoriesID = $categoriesID;
        $this->postID = $postID;
        $this->categoryID = 0;
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
            $this->categoryID = $this->categoriesID[$i];
            $this->prepare();
            $error = !$db->delete($table, $where, $this->prepareStatement);
            $this->prepareStatement = [];
        }

        return !$error;
    }

    /**
     * Metodo que establece los datos a preparar.
     */
    protected function prepare() {
        $this->addPrepare(':' . PostCategory::RELATIONSHIPS_CATEGORY_ID, $this->categoryID, \PDO::PARAM_INT);
        $this->addPrepare(':' . PostCategory::RELATIONSHIPS_POST_ID, $this->postID, \PDO::PARAM_INT);
    }

}
