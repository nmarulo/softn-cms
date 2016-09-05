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
     * @param int $paged [Opcional] Pagina actual
     * @return array
     */
    public function index($paged = 1) {
        return ['data' => $this->dataIndex($paged)];
    }
    
    /**
     * Metodo llamado por la función INDEX.
     * @param int $paged Pagina actual
     * @return array
     */
    abstract protected function dataIndex($paged);

}
