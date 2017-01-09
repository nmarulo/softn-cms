<?php

/**
 * Plantilla para los modulos de modelos.
 */

namespace SoftnCMS\models\admin\base;

/**
 * Interfaz Models
 * @author Nicolás Marulanda P.
 */
interface ModelsInterface {
    
    /**
     * Método que obtiene todos los objetos de la base de datos.
     * @return Models
     */
    public static function selectAll();
    
    /**
     * Método que recibe un lista de datos y retorna un instancia.
     *
     * @param array $data Lista de datos.
     *
     * @return object|bool Si es FALSE, no hay datos.
     */
    public static function getInstanceData($data);
    
    /**
     * Método que obtiene el número total de objetos.
     * @return int
     */
    public static function count();
    
    /**
     * Método que obtiene toda la lista de datos.
     * @return array
     */
    public function getAll();
    
    /**
     * Método que obtiene, según su ID, un dato de la lista.
     *
     * @param int $id Identificador.
     */
    public function getByID($id);
    
    /**
     * Método que recibe un dato y lo agrega a la lista actual.
     *
     * @param object $data
     */
    public function add($data);
    
    /**
     * Método que recibe una lista de datos y los agrega a la lista actual.
     *
     * @param array $data Lista de datos.
     */
    public function addData($data);
    
}
