<?php

/**
 * Modulo modelo: Gestiona la actualización de las categorías.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\models\admin\base\ModelUpdate;

/**
 * Clase CategoryUpdate para gestionar la actualización de las categorías.
 * @author Nicolás Marulanda P.
 */
class CategoryUpdate extends ModelUpdate {
    
    /** @var Category Instancia con los datos sin modificar. */
    private $category;
    
    /** @var string Nombre de la categoría. */
    private $categoryName;
    
    /** @var string Descripción de la categoría. */
    private $categoryDescription;
    
    /**
     * Constructor.
     *
     * @param Category $category            Instancia con los datos sin modificar.
     * @param string   $categoryName        Nombre de la categoría.
     * @param string   $categoryDescription Descripción de la categoría.
     */
    public function __construct($category, $categoryName, $categoryDescription) {
        parent::__construct(Category::getTableName());
        $this->category            = $category;
        $this->categoryName        = $categoryName;
        $this->categoryDescription = $categoryDescription;
    }
    
    /**
     * Método que obtiene la categoría con los datos actualizados.
     * @return Category
     */
    public function getDataUpdate() {
        //Obtiene el primer dato el cual corresponde al id.
        $id = $this->prepareStatement[0]['value'];
        
        return Category::selectByID($id);
    }
    
    /**
     * Método que establece los datos a preparar.
     * @return bool Si es TRUE, no hay datos para actualizar.
     */
    protected function prepare() {
        $this->addPrepare(':' . Category::ID, $this->category->getID(), \PDO::PARAM_INT);
        $this->checkFields($this->category->getCategoryName(), $this->categoryName, Category::CATEGORY_NAME, \PDO::PARAM_STR);
        $this->checkFields($this->category->getCategoryDescription(), $this->categoryDescription, Category::CATEGORY_DESCRIPTION, \PDO::PARAM_STR);
        
        return empty($this->dataColumns);
    }
    
}
