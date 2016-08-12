<?php

/**
 * Clase abstracta.
 */

namespace SoftnCMS\controllers;

use SoftnCMS\controllers\Controller;

/**
 * Clase que contiene los metodos que debe tener cada modulo controlador de la aplicación.
 * @author Nicolás Marulanda P.
 */
abstract class BaseController extends Controller {

    /**
     * Metodo que obtiene los datos y los guarda en la base de datos.
     * @return array
     */
    public function insert() {
        return ['data' => $this->dataInsert()];
    }

    /**
     * Metodo que actualiza los datos segun su identificador.
     * @param int $id
     * @return array
     */
    public function update($id) {
        return ['data' => $this->dataUpdate($id)];
    }

    /**
     * Metodo que borra los datos segun su identificador.
     * @param int $id
     * @return array
     */
    public function delete($id) {
        return ['data' => $this->dataDelete($id)];
    }

    /**
     * Metodo llamado por la función INSTER.
     * @return array
     */
    abstract protected function dataInsert();

    /**
     * Metodo llamado por la función UPDATE.
     * @return array
     */
    abstract protected function dataUpdate($id);

    /**
     * Metodo llamado por la función DELETE.
     * @return array
     */
    abstract protected function dataDelete($id);

    /**
     * Metodo que obtiene los datos de los campos INPUT del formulario.
     * @return array
     */
    abstract protected function getDataInput();
    
}
