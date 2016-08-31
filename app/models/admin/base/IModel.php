<?php

/**
 * Plantilla para los modulos de modelos.
 */

namespace SoftnCMS\models\admin\base;

/**
 *
 * @author Nicolás Marulanda P.
 */
interface IModel {

    /** Identificador. */
    const ID = 'ID';

    /**
     * Metodo que obtiene el nombre de la tabla.
     */
    public static function getTableName();

    /**
     * Metodo que retorna una instancia por defecto.
     */
    public static function defaultInstance();

    /**
     * Metodo que obtiene un objecto segun su "ID".
     * @param int $value Identificador.
     */
    public static function selectByID($value);
    
    /**
     * Metodo que recibe un lista de datos y retorna un instancia.
     * @param array $data Lista de datos.
     * @return User|bool Si es FALSE, no hay datos.
     */
    public static function getInstanceData($data);

    /**
     * Metodo que obtiene el identificador.
     */
    public function getID();
}
