<?php
/**
 * Route.phpp
 */

namespace SoftnCMS\route;

/**
 * Class Route
 * @author Nicolás Marulanda P.
 */
class Route {
    
    /**
     * @var string Identifica el directorio del controlador,
     * en la carpeta "controllers".
     */
    private $directoryController;
    
    /**
     * @var string Identifica el directorio de la vista.
     *             Si no es del tema, el directorio corresponde a las carpetas
     *             dentro de "views".
     */
    private $directoryView;
    
    /** @var string */
    private $controller;
    
    /** @var string */
    private $method;
    
    /** @var int */
    private $parameter;
    
    /**
     * Route constructor.
     */
    public function __construct() {
        $this->directoryController = 'theme';
        $this->directoryView       = 'theme';
        $this->controller          = 'Index';
        $this->method              = 'index';
        $this->parameter           = '';
    }
    
    /**
     * @return string
     */
    public function getDirectoryController() {
        return $this->directoryController;
    }
    
    /**
     * @param string $directoryController
     */
    public function setDirectoryController($directoryController) {
        $this->directoryController = $directoryController;
    }
    
    /**
     * @return string
     */
    public function getController() {
        return $this->controller;
    }
    
    /**
     * @param string $controller
     */
    public function setController($controller) {
        $this->controller = $controller;
    }
    
    /**
     * @return string
     */
    public function getMethod() {
        return $this->method;
    }
    
    /**
     * @param string $method
     */
    public function setMethod($method) {
        $this->method = $method;
    }
    
    /**
     * @return int
     */
    public function getParameter() {
        return $this->parameter;
    }
    
    /**
     * @param int $parameter
     */
    public function setParameter($parameter) {
        $this->parameter = $parameter;
    }
    
    /**
     * @return string
     */
    public function getDirectoryView() {
        return $this->directoryView;
    }
    
    /**
     * @param string $directoryView
     */
    public function setDirectoryView($directoryView) {
        $this->directoryView = $directoryView;
    }
    
}
