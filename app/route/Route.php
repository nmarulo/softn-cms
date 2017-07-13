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
    
    const DIRECTORY_ADMIN   = 'admin';
    
    const DIRECTORY_LOGIN   = 'login';
    
    const DIRECTORY_INSTALL = 'install';
    
    const DIRECTORY_THEME   = 'theme';
    
    /**
     * @var string Identifica el directorio del controlador,
     * en la carpeta "controllers".
     */
    private $directoryController;
    
    /**
     * @var string Nombre del directorio de las vistas del controlador.
     *             Si no es del tema, el directorio corresponde a las carpetas
     *             dentro de "views/{$directoryViews}" o "themes/{$directoryViews}".
     */
    private $directoryViewsController;
    
    /**
     * @var string Nombre del directorio de las vistas. "views" o "themes".
     */
    private $directoryViews;
    
    private $viewPath;
    
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
        $this->directoryController      = 'theme';
        $this->directoryViews           = 'default';
        $this->directoryViewsController = 'index';
        $this->controller               = 'Index';
        $this->method                   = 'index';
        $this->parameter                = '';
        $this->viewPath                 = VIEWS;
    }
    
    /**
     * @return string
     */
    public function getDirectoryViews() {
        return $this->directoryViews;
    }
    
    /**
     * @param string $directoryViews
     */
    public function setDirectoryViews($directoryViews) {
        $this->directoryViews = $directoryViews;
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
    public function getDirectoryViewsController() {
        return $this->directoryViewsController;
    }
    
    /**
     * @param string $directoryViewsController
     */
    public function setDirectoryViewsController($directoryViewsController) {
        $this->directoryViewsController = $directoryViewsController;
    }
    
    /**
     * @return string
     */
    public function getViewPath() {
        return $this->viewPath;
    }
    
    /**
     * @param string $viewPath
     */
    public function setViewPath($viewPath) {
        $this->viewPath = $viewPath;
    }
    
}
