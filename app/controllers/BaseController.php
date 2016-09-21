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
     * @var string Nombre de la pagina actual. Usado para
     *             redireccionar en el metodo delete.
     */
    protected $namePage;

    /**
     * Metodo que obtiene los datos y los guarda en la base de datos.
     * @return array
     */
    public function insert() {
        return ['data' => $this->dataInsert()];
    }

    /**
     * Metodo que actualiza los datos segun su identificador.
     * @param array $data Lista de argumentos.
     * @return array
     */
    public function update($data) {
        return ['data' => $this->dataUpdate($data)];
    }

    /**
     * Metodo que borra los datos segun su identificador.
     * @param array $data Lista de argumentos.
     * @return array
     */
    public function delete($data) {
        global $urlSite;

        $this->dataDelete($data);

        \header("Location: $urlSite" . 'admin/' . $this->namePage . '/paged/' . $data['paged']);
        exit();
    }

    /**
     * Metodo llamado por la función INSTER.
     * @return array
     */
    abstract protected function dataInsert();

    /**
     * Metodo llamado por la función UPDATE.
     * @param array $data Lista de argumentos.
     * @return mixed
     */
    abstract protected function dataUpdate($data);

    /**
     * Metodo llamado por la función DELETE.
     * @param array $data Lista de argumentos.
     */
    abstract protected function dataDelete($data);

    /**
     * Metodo que obtiene los datos de los campos INPUT del formulario.
     * @return array
     */
    abstract protected function getDataInput();
}
