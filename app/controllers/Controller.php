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
     * @return array
     */
    public function index() {
        return ['data' => $this->dataIndex()];
    }
    
    /**
     * Metodo llamado por la función INDEX.
     * @return array
     */
    abstract protected function dataIndex();

}
