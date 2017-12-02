<?php
/**
 * Router.php
 */

namespace SoftnCMS\rute;

use SoftnCMS\controllers\ViewController;
use SoftnCMS\route\Route;
use SoftnCMS\util\Arrays;
use SoftnCMS\util\controller\ControllerInterface;
use SoftnCMS\util\database\DBAbstract;
use SoftnCMS\util\database\DBInterface;
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
    
    /** @var DBInterface */
    private $connectionDB;
    
    /** @var string */
    private $siteUrl;
    
    /** @var \Closure */
    private $funcCheckViewTheme;
    
    /** @var \Closure */
    private $funcSiteUrl;
    
    /**
     * Router constructor.
     */
    public function __construct() {
        Logger::getInstance()
              ->debug('Inicio de la aplicación.');
        $this->connectionDB       = DBAbstract::getNewInstance();
        $this->canCallUserFunc    = TRUE;
        $this->request            = new Request();
        $this->route              = $this->request->getRoute();
        $this->funcCheckViewTheme = NULL;
        $this->funcSiteUrl        = NULL;
        $this->siteUrl            = "";
        $this->events             = [
            self::EVENT_ERROR => function() {
                throw new \Exception('Error');
            },
        ];
    }
    
    /**
     * @return string
     */
    public static function getCurrentNameController() {
        return self::$CURRENT_NAME_CONTROLLER;
    }
    
    /**
     * @param \Closure $closure
     */
    public function setFuncSiteUrl($closure) {
        $this->funcSiteUrl = $closure;
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
        $this->initSiteUrl();
        $this->checkViewTheme();
        $this->events(self::EVENT_INIT_LOAD);
        
        $instanceController = $this->instanceController();
        $method             = $this->getMethod($instanceController);
        $parameter          = $this->getParameter();
        
        ViewController::sendViewData('method', $method);
        $this->setDirectoryView();
        $this->events(self::EVENT_BEFORE_CALL_METHOD);
        
        if ($this->canCallUserFunc) {
            $instanceController->setConnectionDB($this->connectionDB);
            $instanceController->setRequest($this->request);
            $instanceController->setRouter($this);
            call_user_func_array([
                $instanceController,
                $method,
            ], $parameter);
        }
        
        $this->events(self::EVENT_AFTER_CALL_METHOD);
    }
    
    private function initSiteUrl() {
        $siteUrl = '';
        
        if (!defined('INSTALL') && is_callable($this->funcSiteUrl)) {
            $siteUrl = call_user_func($this->funcSiteUrl);
        }
        
        $this->request->setSiteUrl($siteUrl);
        ViewController::setSiteUrl($this->request->getSiteUrl());
    }
    
    private function checkViewTheme() {
        if (is_callable($this->funcCheckViewTheme)) {
            call_user_func($this->funcCheckViewTheme);
        }
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
     * @return ControllerInterface
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
        ViewController::setCurrentDirectory($this->route->getControllerDirectoryName());
        ViewController::setDirectoryViews($this->route->getViewDirectoryName());
        ViewController::setDirectoryViewsController($this->route->getDirectoryNameViewController());
        ViewController::setViewPath($this->route->getViewPath());
    }
    
    /**
     * @return DBInterface
     */
    public function getConnectionDB() {
        return $this->connectionDB;
    }
    
    /**
     * @param DBInterface $connectionDB
     */
    public function setConnectionDB($connectionDB) {
        $this->connectionDB = $connectionDB;
    }
    
    /**
     * @param \Closure $closure
     */
    public function setFuncCheckViewTheme($closure) {
        $this->funcCheckViewTheme = $closure;
    }
    
}
