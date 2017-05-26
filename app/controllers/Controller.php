<?php

/**
 * Clase abstracta.
 */

namespace SoftnCMS\controllers;

use SoftnCMS\models\admin\template\Template;
use SoftnCMS\models\theme\Template as ThemeTemplate;

/**
 * Clase que contiene los métodos que debe tener cada controlador.
 * @author Nicolás Marulanda P.
 */
abstract class Controller {
    
    /**
     * Método que obtiene los datos a mostrar en el modulo vista.
     * Método por defecto de cada controlador.
     *
     * @param array $data Lista de argumentos.
     *
     * @return array
     */
    public function index($data) {
        if(!defined('INSTALL')) {
            Token::generate();
        }
        $output = $this->dataIndex($data);
        
        $template = ThemeTemplate::class;
        
        if (Router::getRequest()
                  ->getRoute() == Router::getRoutes()['admin']
        ) {
            $template = Template::class;
            
        }
        
        $output = array_merge($output, [
            'template' => $template,
        ]);
        
        return ['data' => $output];
    }
    
    /**
     * Método llamado por la función INDEX.
     *
     * @param array $data Lista de argumentos.
     *
     * @return array
     */
    abstract protected function dataIndex($data);
    
}
