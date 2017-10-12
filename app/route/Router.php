<?php
/**
 * Router.php
 */

namespace SoftnCMS\rute;

use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\managers\OptionsManager;
use SoftnCMS\route\Route;
use SoftnCMS\util\Arrays;
use SoftnCMS\util\Logger;
use SoftnCMS\util\Util;

/**
 * Class Router
 * @author Nicolás Marulanda P.
 */
class Router {
    
    const EVENT_INIT_LOAD          = 1;
    
    const EVENT_BEFORE_CALL_METHOD = 2;
    
    const EVENT_AFTER_CALL_METHOD  = 3;
    
    const EVENT_ERROR              = 4;
    
    /** @var string */
    private static $SITE_URL;
    
    /** @var string Directorio del controlador actual. */
    private static $CURRENT_DIRECTORY;
    
    /** @var string Nombre del controlador actual. */
    private static $CURRENT_NAME_CONTROLLER;
    
    /** @var Request */
    private $request;
    
    /** @var Route */
    private $route;
    
    /** @var array */
    private $events;
    
    /** @var bool */
    private $canCallUserFunc;
    
    /**
     * Router constructor.
     */
    public function __construct() {
        $this->canCallUserFunc = TRUE;
        $this->request         = new Request();
        $this->route           = $this->request->getRoute();
        $this->events          = [
            self::EVENT_ERROR => function() {
                throw new \Exception('Error');
            },
        ];
        $optionsManager        = new OptionsManager();
        self::$SITE_URL        = $optionsManager->getSiteUrl($this);
    }
    
    /**
     * @return string
     */
    public static function getSiteURL() {
        return self::$SITE_URL;
    }
    
    /**
     * @return string
     */
    public static function getCurrentDirectory() {
        return self::$CURRENT_DIRECTORY;
    }
    
    /**
     * @return string
     */
    public static function getCurrentNameController() {
        return self::$CURRENT_NAME_CONTROLLER;
    }
    
    /**
     * @return Request
     */
    public function getRequest() {
        return $this->request;
    }
    
    /**
     * @return Route
     */
    public function getRoute() {
        return $this->route;
    }
    
    public function setEvent($event, $callback) {
        $this->events[$event] = $callback;
    }
    
    /**
     * @param bool $canCallUserFunc
     */
    public function setCanCallUserFunc($canCallUserFunc) {
        $this->canCallUserFunc = $canCallUserFunc;
    }
    
    public function load() {
        $this->events(self::EVENT_INIT_LOAD);
        
        $instanceController = $this->instanceController();
        $method             = $this->getMethod($instanceController);
        $parameter          = $this->getParameter();
        
        ViewController::sendViewData('method', $method);
        $this->setDirectoryView();
        $this->events(self::EVENT_BEFORE_CALL_METHOD);
        
        if ($this->canCallUserFunc) {
            call_user_func_array([
                $instanceController,
                $method,
            ], $parameter);
        }
        
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
        
        if ($callback !== FALSE) {
            if (is_callable($callback)) {
                $callback();
            } else {
                Logger::getInstance()
                      ->debug('La función no es ejecutable.', [
                          'function'  => $callback,
                          'eventKey'  => $event,
                          'eventList' => $this->events,
                      ]);
            }
        }
    }
    
    /**
     * @return mixed
     */
    private function instanceController() {
        $controllerName      = $this->route->getControllerName() . 'Controller';
        $controllerDirectory = $this->route->getControllerDirectoryName();
        $pathController      = CONTROLLERS . $controllerDirectory . DIRECTORY_SEPARATOR;
        $fileController      = "$controllerName.php";
        
        if (!file_exists($pathController . $fileController)) {
            Logger::getInstance()
                  ->warning('El controlador no existe.', [
                      'path'               => $pathController,
                      'fileControllerName' => $fileController,
                  ]);
            $filesControllersName = Util::getFilesAndDirectories($pathController);
            Logger::getInstance()
                  ->debug('Comprobando lista de controladores.', [
                      'filesControllersName' => $filesControllersName,
                  ]);
            $filter = array_filter($filesControllersName, function($file) use ($fileController) {
                return strcasecmp($file, $fileController) == 0;
            });
            
            if (empty($filter)) {
                Logger::getInstance()
                      ->error('Controlador no encontrado.');
                $this->events(self::EVENT_ERROR);
            } else {
                $controllerName = Util::removeExtension(Arrays::get(array_merge($filter), 0));
            }
        }
        
        self::$CURRENT_NAME_CONTROLLER = str_replace('controller', '', strtolower($controllerName));
        $controller                    = NAMESPACE_CONTROLLERS . "$controllerDirectory\\$controllerName";
        
        return new $controller();
    }
    
    /**
     * @param $instanceController
     *
     * @return string
     */
    private function getMethod($instanceController) {
        $method = $this->route->getMethodName();
        
        if (!method_exists($instanceController, $method)) {
            Logger::getInstance()
                  ->debug('El método no existe. Estableciendo método por defecto.', [
                      'currentMethod' => $method,
                      'defaultMethod' => Route::DEFAULT_METHOD,
                  ]);
            $method = Route::DEFAULT_METHOD;
            $this->route->setMethodName($method);
        }
        
        return $method;
    }
    
    /**
     * @return array
     */
    private function getParameter() {
        $parameter = $this->route->getParameter();
        
        return [$parameter];
    }
    
    private function setDirectoryView() {
        self::$CURRENT_DIRECTORY = $this->route->getControllerDirectoryName();
        ViewController::setDirectoryViews($this->route->getViewDirectoryName());
        ViewController::setDirectoryViewsController($this->route->getDirectoryNameViewController());
        ViewController::setViewPath($this->route->getViewPath());
    }
}
