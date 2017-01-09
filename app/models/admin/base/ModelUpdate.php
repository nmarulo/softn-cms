<?php

/**
 * Clase abstracta.
 */

namespace SoftnCMS\models\admin\base;

use SoftnCMS\controllers\DBController;

/**
 * Clase ModelUpdate
 * @author Nicolás Marulanda P.
 */
abstract class ModelUpdate {
    
    /** @var string Campos que serán actualizados. */
    protected $dataColumns;
    
    /** @var array Lista con los indices, valores y tipos de datos para la consulta. */
    protected $prepareStatement;
    
    /** @var string Nombre de la tabla. * */
    private $table;
    
    /**
     * Constructor.
     *
     * @param string $table Nombre de la tabla.
     */
    public function __construct($table) {
        $this->dataColumns      = "";
        $this->prepareStatement = [];
        $this->table            = $table;
    }
    
    /**
     * Método que actualiza los datos del objeto en la base de datos.
     * @return bool Si es TRUE, se realizo correctamente.
     */
    public function update() {
        $db        = DBController::getConnection();
        $parameter = Model::ID;
        $where     = "$parameter = :$parameter";
        
        /*
         * Si no hay datos, no se ejecuta la consulta. 
         * Se retorna TRUE para evitar un error.
         */
        if ($this->prepare()) {
            return \TRUE;
        }
        
        return $db->update($this->table, $this->dataColumns, $where, $this->prepareStatement);
    }
    
    /**
     * Método que establece los datos a preparar.
     * @return bool Si es TRUE, no hay datos para actualizar.
     */
    abstract protected function prepare();
    
    /**
     * Método que obtiene el objeto con los datos actualizados.
     * @return Object
     */
    abstract public function getDataUpdate();
    
    /**
     * Método que comprueba si el nuevo dato es diferente al de la base de datos,
     * de ser asi el campo sera actualizado.
     *
     * @param string|int $oldData  Dato actual.
     * @param string|int $newData  Dato nuevo.
     * @param string     $column   Nombre de la columna en la tabla.
     * @param int        $dataType Tipo de dato.
     */
    protected function checkFields($oldData, $newData, $column, $dataType) {
        if ($oldData != $newData) {
            $parameter = ':' . $column;
            $this->addSetDataSQL($column, $parameter);
            $this->addPrepare($parameter, $newData, $dataType);
        }
    }
    
    /**
     * Método que agrega los datos que serán actualizados.
     *
     * @param string $column Nombre de la columna en la tabla.
     * @param string $data   Nuevo valor.
     */
    protected function addSetDataSQL($column, $data) {
        $this->dataColumns .= empty($this->dataColumns) ? '' : ', ';
        $this->dataColumns .= "$column = $data";
    }
    
    /**
     * Método que guarda los datos establecidos.
     *
     * @param string $parameter Indice a buscar. EJ: ":ID"
     * @param string $value     Valor del indice.
     * @param int    $dataType  Tipo de dato. EJ: \PDO::PARAM_*
     */
    protected function addPrepare($parameter, $value, $dataType) {
        $this->prepareStatement[] = DBController::prepareStatement($parameter, $value, $dataType);
    }
    
}
