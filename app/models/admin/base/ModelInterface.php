<?php

/**
 * Plantilla para los modulos de modelos.
 */

namespace SoftnCMS\models\admin\base;

/**
 * Interfaz ModelInterface
 * @author Nicolás Marulanda P.
 */
interface ModelInterface {
    
    /** Identificador. */
    const ID = 'ID';
    
    /**
     * Método que obtiene el nombre de la tabla.
     */
    public static function getTableName();
    
    /**
     * Método que retorna una instancia por defecto.
     */
    public static function defaultInstance();
    
    /**
     * Método que obtiene un objeto según su "ID".
     *
     * @param int $value Identificador.
     */
    public static function selectByID($value);
    
    /**
     * Método que recibe un lista de datos y retorna un instancia.
     *
     * @param array $data Lista de datos.
     *
     * @return Object|bool Si es FALSE, no hay datos.
     */
    public static function getInstanceData($data);
    
    /**
     * Método que obtiene el identificador.
     */
    public function getID();
}
