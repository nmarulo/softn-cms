<?php
/**
 * Router.php
 */

namespace SoftnCMS\rute;

use Gettext\Translations;
use Gettext\Translator;
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\managers\OptionsManager;
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
    
    /** @var string */
    private static $SITE_URL;
    
    /** @var string Directorio del controlador actual. */
    private static $CURRENT_DIRECTORY;
    
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
        $this->request  = new Request();
        $this->route    = $this->request->getRoute();
        $this->events   = [
            self::EVENT_ERROR => function() {
                throw new \Exception('Error');
            },
        ];
        $optionsManager = new OptionsManager();
        self::$SITE_URL = $optionsManager->getSiteUrl($this);
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
    
    /**
     * @return mixed
     */
    private function instanceController() {
        $controllerName      = $this->route->getControllerName();
        $controllerName      .= 'Controller';
        $controllerDirectory = $this->route->getControllerDirectoryName();
        $pathController      = CONTROLLERS . $controllerDirectory . DIRECTORY_SEPARATOR;
        $pathController      .= "$controllerName.php";
        
        if (!file_exists($pathController)) {
            $this->events(self::EVENT_ERROR);
        }
        
        $controller = NAMESPACE_CONTROLLERS . "$controllerDirectory\\$controllerName";
        
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
            $method = 'index';
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
