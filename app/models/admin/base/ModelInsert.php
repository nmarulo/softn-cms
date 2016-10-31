<?php

/**
 * Clase abstracta.
 */

namespace SoftnCMS\models\admin\base;

use SoftnCMS\controllers\DBController;

/**
 * Clase ModelInsert
 * @author Nicolás Marulanda P.
 */
abstract class ModelInsert {
    
    /** @var array Lista con los indices, valores y tipos de datos para la consulta. */
    protected $prepareStatement;
    
    /** @var int Identificador del INSERT realizado. */
    protected $lastInsertId;
    
    /** @var string Nombre de la tabla. */
    private $table;
    
    /** @var string Nombre de las columnas. */
    private $columns;
    
    /** @var string Nombre de los indices para preparar la consulta. */
    private $values;
    
    /**
     * Constructor.
     *
     * @param string $table   Nombre de la tabla.
     * @param string $columns Nombre de las columnas.
     * @param string $values  Nombre de los indices para preparar la consulta.
     */
    public function __construct($table, $columns, $values) {
        $this->prepareStatement = [];
        $this->lastInsertId     = 0;
        $this->table            = $table;
        $this->columns          = $columns;
        $this->values           = $values;
    }
    
    /**
     * Método que realiza el proceso de insertar los datos.
     * @return bool Si es TRUE, todo se realizo correctamente.
     */
    public function insert() {
        $db = DBController::getConnection();
        $this->prepare();
        
        //En caso de error
        if ($db->insert($this->table, $this->columns, $this->values, $this->prepareStatement)) {
            $this->lastInsertId = $db->lastInsertId();
            
            return \TRUE;
        }
        
        return \FALSE;
    }
    
    /**
     * Método que establece los datos a preparar.
     */
    abstract protected function prepare();
    
    /**
     * Método que obtiene el identificador del nuevo dato.
     * @return int
     */
    public function getLastInsertId() {
        return $this->lastInsertId;
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
