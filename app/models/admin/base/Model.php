<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SoftnCMS\models\admin\base;

use SoftnCMS\models\admin\base\IModel;
use SoftnCMS\models\admin\base\BaseModels;

/**
 * Description of Model
 *
 * @author MaruloPC-Desk
 */
abstract class Model extends BaseModels implements IModel {
    
    /**
     * Metodo que recibe un lista de datos y retorna un instancia.
     * @param array $data Lista de datos.
     * @param string $class Nombre de la clase incluido su namespace.
     * @return object|bool Si es FALSE, no hay datos.
     */
    public static function getInstance($data, $class) {
        if (empty($data)) {
            return \FALSE;
        }

        return new $class($data[0]);
    }
}
