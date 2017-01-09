<?php

/**
 * Modulo modelo: Gestiona grupos de etiquetas.
 */
namespace SoftnCMS\models\admin;

use SoftnCMS\models\admin\base\Models;

/**
 * Clase Terms para gestionar grupos de etiquetas.
 * @author Nicolás Marulanda P.
 */
class Terms extends Models {
    
    /**
     * Constructor.
     */
    public function __construct() {
        parent::__construct(Term::getTableName(), __CLASS__);
    }
    
    /**
     * Método que obtiene todas las etiquetas de la base de datos.
     * @return Terms|bool Si es FALSE, no hay datos.
     */
    public static function selectAll() {
        $select = self::select(Term::getTableName());
        
        return self::getInstanceData($select);
    }
    
    /**
     * Método que recibe un lista de datos y retorna un instancia.
     *
     * @param array $data Lista de datos.
     *
     * @return Terms|bool Si es FALSE, no hay datos.
     */
    public static function getInstanceData($data) {
        return parent::getInstance($data, __CLASS__);
    }
    
    /**
     * Método que obtiene un número limitado de datos.
     *
     * @param string $limit
     *
     * @return Terms|bool Si es FALSE, no hay datos.
     */
    public static function selectByLimit($limit) {
        $select = self::select(Term::getTableName(), '', [], '*', $limit);
        
        return self::getInstanceData($select);
    }
    
    /**
     * Método que obtiene el número total de datos.
     * @return int
     */
    public static function count() {
        return parent::countData(Term::getTableName());
    }
    
    /**
     * Método que recibe una lista de datos y los agrega a la lista actual.
     *
     * @param array $data Lista de datos.
     */
    public function addData($data) {
        foreach ($data as $value) {
            $this->add(new Term($value));
        }
    }
    
}
