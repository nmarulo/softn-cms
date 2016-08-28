<?php

/**
 * Plantilla para los modulos de modelos.
 */

namespace SoftnCMS\models\admin\base;

/**
 * Description of Models
 *
 * @author Nicolás Marulanda P.
 */
interface IModels{

    /**
     * Metodo que obtiene todos los objetos de la base de datos.
     * @return Models
     */
    public static function selectAll();
    
    /**
     * Metodo que recibe un lista de datos y retorna un instancia.
     * @param array $data Lista de datos.
     * @return object|bool Si es FALSE, no hay datos.
     */
    public static function getInstanceData($data);
    
    /**
     * Metodo que obtiene toda la lista de datos.
     * @return array
     */
    public function getAll();

    /**
     * Metodo que obtiene, segun su ID, un dato de la lista.
     * @param int $id Identificador.
     */
    public function getByID($id);

    /**
     * Metodo que recibe un dato y lo agrega a la lista actual.
     * @param object $data
     */
    public function add($data);

    /**
     * Metodo que recibe una lista de datos y los agrega a la lista actual.
     * @param array $data Lista de datos.
     */
    public function addData($data);

    /**
     * Metodo que obtiene el número total de objetos.
     * @return int
     */
    public function count();
}
