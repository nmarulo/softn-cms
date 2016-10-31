<?php

/**
 * Clase abstracta base.
 */

namespace SoftnCMS\models\admin\base;

/**
 * Clase Models
 * @author Nicolás Marulanda P.
 */
abstract class Models extends BaseModels implements ModelsInterface {
    
    /**
     * Lista, donde el indice o clave corresponde al ID.
     * @var array
     */
    protected $data;
    
    /** @var string Nombre de la tabla. */
    private $table;
    
    /** @var string $class Nombre de la clase incluido su namespace. */
    private $class;
    
    /**
     * Constructor.
     *
     * @param string $table Nombre de la tabla.
     * @param string $class Nombre de la clase incluido su namespace.
     */
    public function __construct($table, $class) {
        $this->data  = [];
        $this->table = $table;
        $this->class = $class;
    }
    
    /**
     * Método que obtiene el número total de datos.
     *
     * @param string $table
     *
     * @return int
     */
    protected static function countData($table) {
        $columns = 'COUNT(*) AS count';
        $select  = self::select($table, '', [], $columns);
        
        return $select[0]['count'];
    }
    
    /**
     * Método que obtiene, según su identificador, un dato de la lista.
     *
     * @param int $id Identificador.
     *
     * @return object
     */
    public function getByID($id) {
        return $this->data[$id];
    }
    
    /**
     * Método que recibe un dato y lo agrega a la lista actual.
     *
     * @param object $data
     */
    public function add($data) {
        $this->data[$data->getID()] = $data;
    }
    
    /**
     * Método que obtiene los últimos datos.
     *
     * @param int $limit Número de datos a obtener.
     *
     * @return array Lista de datos.
     */
    public function lastData($limit) {
        $output = [];
        
        if (empty($this->data)) {
            $select = self::select($this->table, '', [], '*', $limit);
            $data   = self::getInstance($select, $this->class);
            
            if (!empty($data)) {
                $output = $data->getAll();
            }
        } else {
            $output = \array_slice($this->getAll(), 0, $limit, \TRUE);
        }
        
        return $output;
    }
    
    /**
     * Método que recibe un lista de datos y retorna un instancia.
     *
     * @param array  $data  Lista de datos.
     * @param string $class Nombre de la clase incluido su namespace.
     *
     * @return object|bool Si es FALSE, no hay datos.
     */
    protected static function getInstance($data, $class) {
        if ($data === \FALSE) {
            return \FALSE;
        }
        
        $output = new $class;
        $output->addData($data);
        
        return $output;
    }
    
    /**
     * Método que obtiene toda la lista de datos.
     * @return array
     */
    public function getAll() {
        return $this->data;
    }
    
}
