<?php

/**
 * Controlador de rutas.
 */

namespace SoftnCMS\controllers;

/**
 * Controlador de ruta, incluye los controladores solicitados.
 *
 * @author Nicolás Marulanda P.
 */
class Router {
    
    /** @var \SoftnCMS\controllers\Request Instancia de la clase Request. */
    private $request;
    
    public function __construct() {
        $this->request = new \SoftnCMS\controllers\Request();
        
        if($this->request->isFolder()){
            $controller = \ABSPATH . \CONTROLLERS . $this->request->getFolder();
            $controller .= \DIRECTORY_SEPARATOR . $this->request->getController();
            
            if(\is_readable($controller)){
                include $controller;
            }
        }
    }
    
    
}
