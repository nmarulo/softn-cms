<?php
/**
 * Router.php
 */

namespace SoftnCMS\rute;

use SoftnCMS\controllers\ViewController;
use SoftnCMS\route\Route;
use SoftnCMS\util\Arrays;

/**
 * Class Router
 * @author Nicolás Marulanda P.
 */
class Router {
    
    const EVENT_INIT_LOAD          = 1;
    
    const EVENT_BEFORE_CALL_METHOD = 2;
    
    const EVENT_AFTER_CALL_METHOD  = 3;
    
    const EVENT_ERROR              = 4;
    
    /** @var Request */
    private $request;
    
    /** @var Route */
    private $route;
    
    /** @var array */
    private $events;
    
    /**
     * Router constructor.
     */
    public function __construct() {
        $this->request = new Request();
        $this->route   = $this->request->getRoute();
        $this->events  = [
            self::EVENT_ERROR => function() {
                throw new \Exception('Error');
            },
        ];
    }
    
    public function setEvent($event, $callback) {
        $this->events[$event] = $callback;
    }
    
    public function load() {
        $this->events(self::EVENT_INIT_LOAD);
        
        $instanceController = $this->instanceController();
        $method             = $this->getMethod($instanceController);
        $parameter          = $this->getParameter();
        
        ViewController::sendViewData('method', $method);
        $this->setDirectoryView();
        $this->events(self::EVENT_BEFORE_CALL_METHOD);
        
        call_user_func_array([
            $instanceController,
            $method,
        ], $parameter);
        
        $this->events(self::EVENT_AFTER_CALL_METHOD);
    }
    
    private function events($event) {
        $callback = FALSE;
        
        switch ($event) {
            case self::EVENT_ERROR:
                $callback = Arrays::get($this->events, self::EVENT_ERROR);
                break;
            case self::EVENT_AFTER_CALL_METHOD:
                $callback = Arrays::get($this->events, self::EVENT_AFTER_CALL_METHOD);
                break;
            case self::EVENT_BEFORE_CALL_METHOD:
                $callback = Arrays::get($this->events, self::EVENT_BEFORE_CALL_METHOD);
                break;
            case self::EVENT_INIT_LOAD:
                $callback = Arrays::get($this->events, self::EVENT_INIT_LOAD);
                break;
        }
        
        if ($callback !== FALSE && is_callable($callback)) {
            $callback();
        }
    }
    
    private function instanceController() {
        $controllerName      = $this->route->getController();
        $controllerName      .= 'Controller';
        $controllerDirectory = $this->route->getDirectoryController();
        $pathController      = CONTROLLERS . $controllerDirectory . DIRECTORY_SEPARATOR;
        $pathController      .= "$controllerName.php";
        
        if (!file_exists($pathController)) {
            $this->events(self::EVENT_ERROR);
        }
        
        $controller = NAMESPACE_CONTROLLERS . "$controllerDirectory\\$controllerName";
        
        return new $controller();
    }
    
    private function getMethod($instanceController) {
        $method = $this->route->getMethod();
        
        if (!method_exists($instanceController, $method)) {
            $method = 'index';
            $this->route->setMethod($method);
        }
        
        return $method;
    }
    
    private function getParameter() {
        $parameter = $this->route->getParameter();
        
        return [$parameter];
    }
    
    private function setDirectoryView() {
        ViewController::setDirectoryVIEW($this->route->getDirectoryController());
        ViewController::setDirectoryCONTROLLER($this->route->getDirectoryViewController());
    }
}
