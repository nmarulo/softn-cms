<?php

/**
 * Clase abstracta.
 */

namespace SoftnCMS\controllers;

use SoftnCMS\Helpers\ArrayHelp;
use SoftnCMS\Helpers\Helps;
use SoftnCMS\models\admin\template\Template;

/**
 * Clase BaseController que contiene los métodos que debe tener cada modulo controlador de la aplicación.
 * @author Nicolás Marulanda P.
 */
abstract class BaseController extends Controller {
    
    /**
     * Método que obtiene los datos y los guarda en la base de datos.
     * @return array
     */
    public function insert() {
        Token::generate();
        $output = $this->dataInsert();
        $output = array_merge($output, [
            'template' => Template::class,
        ]);
        
        return ['data' => $output];
    }
    
    /**
     * Método llamado por la función INSERT.
     * @return array
     */
    abstract protected function dataInsert();
    
    /**
     * Método que actualiza los datos según su identificador.
     *
     * @param array $data Lista de argumentos.
     *
     * @return array
     */
    public function update($data) {
        Token::generate();
        $output = $this->dataUpdate($data);
        $output = array_merge($output, [
            'template' => Template::class,
        ]);
        
        return ['data' => $output];
    }
    
    /**
     * Método llamado por la función UPDATE.
     *
     * @param array $data Lista de argumentos.
     *
     * @return array
     */
    abstract protected function dataUpdate($data);
    
    /**
     * Método que borra los datos según su identificador.
     *
     * @param array $data Lista de argumentos.
     */
    public function delete($data) {
        $paged    = '';
        $getPaged = ArrayHelp::get($data, 'paged');
        
        if (!empty($getPaged)) {
            $paged = Pagination::getRoute() . "/$getPaged";
        }
        
        $this->dataDelete($data);
        Helps::redirectRoute($paged);
    }
    
    /**
     * Método llamado por la función DELETE.
     *
     * @param array $data Lista de argumentos.
     */
    abstract protected function dataDelete($data);
    
    /**
     * Método que obtiene los datos de los campos INPUT del formulario.
     * @return array|bool
     */
    abstract protected function getDataInput();
}
