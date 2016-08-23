<?php

/**
 * Modulo del modelo de categorías.
 * Gestiona la actualización de las categorías.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\controllers\DBController;
use SoftnCMS\models\admin\Category;

/**
 * Clase que gestiona la actualización de las categorías.
 *
 * @author Nicolás Marulanda P.
 */
class CategoryUpdate {

    /** @var Category Instancia con los datos sin modificar. */
    private $category;

    /** @var string Campos que seran actualizados. */
    private $dataColumns;

    /** @var array Lista con los indices, valores y tipos de datos para la consulta. */
    private $prepareStatement;

    /** @var string Nombre de la categoría. */
    private $categoryName;

    /** @var string Descripción de la categoría. */
    private $categoryDescription;

    /**
     * Constructor.
     * @param Category $category Instancia con los datos sin modificar.
     * @param string $categoryName Nombre de la categoría.
     * @param string $categoryDescription Descripción de la categoría.
     */
    public function __construct($category, $categoryName, $categoryDescription) {
        $this->category = $category;
        $this->prepareStatement = [];
        $this->categoryName = $categoryName;
        $this->categoryDescription = $categoryDescription;
    }

    /**
     * Metodo que actualiza los datos en la base de datos.
     * @return bool Si es TRUE, todo se realizo correctamente.
     */
    public function update() {
        $db = DBController::getConnection();
        $table = Category::getTableName();
        $parameter = ':id';
        $where = "ID = $parameter";
        $newData = $this->category->getID();
        $dataType = \PDO::PARAM_INT;
        $this->addPrepare($parameter, $newData, $dataType);

        /*
         * Si no hay datos, no se ejecuta la consulta. 
         * Se retorna TRUE para evitar un error.
         */
        if ($this->prepare()) {
            return \TRUE;
        }

        return $db->update($table, $this->dataColumns, $where, $this->prepareStatement);
    }

    /**
     * Metodo que obtiene la categoría con los datos actualizados.
     * @return Category
     */
    public function getCategory() {
        $db = DBController::getConnection();
        $columns = '*';
        $where = 'ID = :id';
        $fetch = 'fetchAll';
        $table = Category::getTableName();
        //Obtiene el primer dato el cual corresponde al id.
        $prepare = [$this->prepareStatement[0]];
        $select = $db->select($table, $fetch, $where, $prepare, $columns);
        $category = new Category($select[0]);

        return $category;
    }

    /**
     * Metodo que establece los datos a preparar.
     * @return bool Si es TRUE, no hay datos para actualizar.
     */
    private function prepare() {
        $this->checkFields($this->category->getCategoryName(), $this->categoryName, Category::CATEGORY_NAME, \PDO::PARAM_STR);
        $this->checkFields($this->category->getCategoryDescription(), $this->categoryDescription, Category::CATEGORY_DESCRIPTION, \PDO::PARAM_STR);

        return empty($this->dataColumns);
    }

    /**
     * Metodo que comprueba si el nuevo dato es diferente al de la base de datos, 
     * de ser asi el campo sera actualizado.
     * @param string|int $oldData Dato actual.
     * @param string|int $newData Dato nuevo.
     * @param string $column Nombre de la columna en la tabla.
     * @param int $dataType Tipo de dato.
     */
    private function checkFields($oldData, $newData, $column, $dataType) {
        if ($oldData != $newData) {
            $parameter = ':' . $column;
            $this->addSetDataSQL($column, $parameter);
            $this->addPrepare($parameter, $newData, $dataType);
        }
    }

    /**
     * Metodo que agrega los datos que seran actualizados.
     * @param string $column Nombre de la columna en la tabla.
     * @param string $data Nuevo valor.
     */
    private function addSetDataSQL($column, $data) {
        $this->dataColumns .= empty($this->dataColumns) ? '' : ', ';
        $this->dataColumns .= "$column = $data";
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
