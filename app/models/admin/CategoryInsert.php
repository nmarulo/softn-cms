<?php

/**
 * Modulo del modelo de categorías.
 * Gestiona el proceso de insertar una categoría.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\controllers\DBController;
use SoftnCMS\models\admin\Category;

/**
 * Clase que gestiona el proceso de insertar una categoría.
 *
 * @author Nicolás Marulanda P.
 */
class CategoryInsert {

    /** @var string Nombre de las columnas. */
    private static $COLUMNS = Category::CATEGORY_NAME . ', ' . Category::CATEGORY_DESCRIPTION . ', ' . Category::CATEGORY_COUNT;

    /** @var string Nombre de los indices para preparar la consulta. */
    private static $VALUES = ':' . Category::CATEGORY_NAME . ', :' . Category::CATEGORY_DESCRIPTION . ', :' . Category::CATEGORY_COUNT;

    /** @var array Lista con los indices, valores y tipos de datos para la consulta. */
    private $prepareStatement;

    /** @var int Identificador del INSERT realizado. */
    private $lastInsertId;

    /** @var string Nombre de la categoría. */
    private $categoryName;

    /** @var string Descripción de la categoría. */
    private $categoryDescription;

    public function __construct($categoryName, $categoryDescription) {
        $this->prepareStatement = [];
        $this->categoryName = $categoryName;
        $this->categoryDescription = $categoryDescription;
    }

    /**
     * Metodo que realiza el proceso de insertar la categoría en la base de datos.
     * @return bool Si es TRUE, todo se realizo correctamente.
     */
    public function insert() {
        $db = DBController::getConnection();
        $table = Category::getTableName();
        $this->prepare();

        if ($db->insert($table, self::$COLUMNS, self::$VALUES, $this->prepareStatement)) {
            $this->lastInsertId = $db->lastInsertId();
            return \TRUE;
        }

        return \FALSE;
    }

    /**
     * Metodo que obtiene el identificador del nuevo comentario.
     * @return int
     */
    public function getLastInsertId() {
        return $this->lastInsertId;
    }

    /**
     * Metodo que establece los datos a preparar.
     */
    private function prepare() {
        $this->addPrepare(':' . Category::CATEGORY_NAME, $this->categoryName, \PDO::PARAM_STR);
        $this->addPrepare(':' . Category::CATEGORY_DESCRIPTION, $this->categoryName, \PDO::PARAM_STR);
        $this->addPrepare(':' . Category::CATEGORY_COUNT, 0, \PDO::PARAM_INT);
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
