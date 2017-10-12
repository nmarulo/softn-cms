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
    
    const DEFAULT_METHOD                    = 'index';
    
    const CONTROLLER_DIRECTORY_NAME_ADMIN   = 'admin';
    
    const CONTROLLER_DIRECTORY_NAME_LOGIN   = 'login';
    
    const CONTROLLER_DIRECTORY_NAME_INSTALL = 'install';
    
    const CONTROLLER_DIRECTORY_NAME_THEME   = 'theme';
    
    /**
     * @var string Identifica el directorio del controlador,
     * en la carpeta "controllers".
     */
    private $controllerDirectoryName;
    
    /**
     * @var string Nombre del directorio de las vistas del controlador.
     *             Si no es del tema, el directorio corresponde a las carpetas
     *             dentro de "views/{$directoryViews}" o "themes/{$directoryViews}".
     */
    private $directoryNameViewController;
    
    /**
     * @var string Nombre del directorio que contiene las vistas del controlador.
     *             Ejemplo:
     *                  /view/{$viewDirectoryName}
     *                  /themes/{$viewDirectoryName}
     */
    private $viewDirectoryName;
    
    private $viewPath;
    
    /** @var string */
    private $controllerName;
    
    /** @var string */
    private $methodName;
    
    /** @var int */
    private $parameter;
    
    /**
     * Route constructor.
     */
    public function __construct() {
        $this->controllerDirectoryName     = 'theme';
        $this->viewDirectoryName           = 'default';
        $this->directoryNameViewController = 'index';
        $this->controllerName              = 'Index';
        $this->methodName                  = self::DEFAULT_METHOD;
        $this->parameter                   = '';
        $this->viewPath                    = VIEWS;
    }
    
    /**
     * @return string
     */
    public function getViewDirectoryName() {
        return $this->viewDirectoryName;
    }
    
    /**
     * @param string $viewDirectoryName
     */
    public function setViewDirectoryName($viewDirectoryName) {
        $this->viewDirectoryName = $viewDirectoryName;
    }
    
    /**
     * @return string
     */
    public function getControllerDirectoryName() {
        return $this->controllerDirectoryName;
    }
    
    /**
     * @param string $controllerDirectoryName
     */
    public function setControllerDirectoryName($controllerDirectoryName) {
        $this->controllerDirectoryName = $controllerDirectoryName;
    }
    
    /**
     * @return string
     */
    public function getControllerName() {
        return $this->controllerName;
    }
    
    /**
     * @param string $controllerName
     */
    public function setControllerName($controllerName) {
        $this->controllerName = $controllerName;
    }
    
    /**
     * @return string
     */
    public function getMethodName() {
        return $this->methodName;
    }
    
    /**
     * @param string $methodName
     */
    public function setMethodName($methodName) {
        $this->methodName = $methodName;
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
    public function getDirectoryNameViewController() {
        return $this->directoryNameViewController;
    }
    
    /**
     * @param string $directoryNameViewController
     */
    public function setDirectoryNameViewController($directoryNameViewController) {
        $this->directoryNameViewController = $directoryNameViewController;
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
