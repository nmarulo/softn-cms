<?php

/**
 * Modulo modelo: Gestiona el proceso de insertar una categoría.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\models\admin\base\ModelInsert;

/**
 * Clase CategoryInsert para gestionar el proceso de insertar una categoría.
 * @author Nicolás Marulanda P.
 */
class CategoryInsert extends ModelInsert {
    
    /** @var string Nombre de las columnas. */
    private static $COLUMNS = Category::CATEGORY_NAME . ', ' . Category::CATEGORY_DESCRIPTION . ', ' . Category::CATEGORY_COUNT;
    
    /** @var string Nombre de los indices para preparar la consulta. */
    private static $VALUES = ':' . Category::CATEGORY_NAME . ', :' . Category::CATEGORY_DESCRIPTION . ', :' . Category::CATEGORY_COUNT;
    
    /** @var string Nombre de la categoría. */
    private $categoryName;
    
    /** @var string Descripción de la categoría. */
    private $categoryDescription;
    
    public function __construct($categoryName, $categoryDescription) {
        parent::__construct(Category::getTableName(), self::$COLUMNS, self::$VALUES);
        $this->categoryName        = $categoryName;
        $this->categoryDescription = $categoryDescription;
    }
    
    /**
     * Método que establece los datos a preparar.
     */
    protected function prepare() {
        $this->addPrepare(':' . Category::CATEGORY_NAME, $this->categoryName, \PDO::PARAM_STR);
        $this->addPrepare(':' . Category::CATEGORY_DESCRIPTION, $this->categoryDescription, \PDO::PARAM_STR);
        $this->addPrepare(':' . Category::CATEGORY_COUNT, 0, \PDO::PARAM_INT);
    }
    
}
