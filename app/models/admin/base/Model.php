<?php

/**
 * Clase abstracta base.
 */

namespace SoftnCMS\models\admin\base;

/**
 * Clase Model
 * @author Nicolás Marulanda P.
 */
abstract class Model extends BaseModels implements ModelInterface {
    
    /**
     * Método que recibe un lista de datos y retorna un instancia.
     *
     * @param array  $data  Lista de datos.
     * @param string $class Nombre de la clase incluido su namespace.
     *
     * @return object|bool Si es FALSE, no hay datos.
     */
    public static function getInstance($data, $class) {
        if (empty($data)) {
            return \FALSE;
        }
        
        return new $class($data[0]);
    }
}
