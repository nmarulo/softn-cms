<?php

/**
 * Clase abstracta.
 */

namespace SoftnCMS\controllers;

/**
 * Clase que contiene los metodos que debe tener cada controlador.
 * @author Nicolás Marulanda P.
 */
abstract class Controller {

    /**
     * Metodo que obtiene los datos a mostrar en el modulo vista. 
     * Metodo por defecto de cada controlador.
     * @param array $data Lista de argumentos.
     * @return array
     */
    public function index($data) {
        return ['data' => $this->dataIndex($data)];
    }
    
    /**
     * Metodo llamado por la función INDEX.
     * @param array $data Lista de argumentos.
     * @return array
     */
    abstract protected function dataIndex($data);

}
