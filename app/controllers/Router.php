<?php

/**
 * Controlador de rutas.
 */

namespace SoftnCMS\controllers;

/**
 * Clase que ejecuta la acción enviada por url.
 * @author Nicolás Marulanda P.
 */
class Router {
    
    private static $ROUTES    = [
        'default'  => '',
        'admin'    => 'admin',
        'login'    => 'login',
        'register' => 'register',
        'logout'   => 'logout',
    ];
    
    private static $NAMESPACE = [
        'default'  => '',
        'admin'    => '',
        'login'    => '',
        'register' => '',
        'logout'   => '',
    ];
    
    private static $PATH      = [
        'default'  => '',
        'admin'    => '',
        'login'    => '',
        'register' => '',
        'logout'   => '',
        '',
    ];
    
    private static $DEFAULT   = [
        'default' => [
            'controller' => 'Index',
            'method'     => 'index',
            'arguments'  => ['data' => []],
            'namespace'  => '',
            'path'       => '',
            'theme'      => 'default',
        ],
    ];
    
    private static $DATA      = [];
    
    private static $REQUEST   = NULL;
    
    private        $events;
    
    public function __construct() {
        $this->events                  = [];
        self::$REQUEST                 = new Request();
        $this->events['afterCallFunc'] = NULL;
        $this->events['error']         = function() {
            die('Error');
        };
    }
    
    public static function getRequest() {
        return self::$REQUEST;
    }
    
    public static function getRoutes() {
        return self::$ROUTES;
    }
    
    public static function setRoutes($key, $values) {
        self::$ROUTES[$key] = $values;
    }
    
    public static function getPath() {
        return self::$PATH;
    }
    
    public static function setPath($key, $values) {
        self::$PATH[$key] = $values;
    }
    
    public static function getNamespaces() {
        return self::$NAMESPACE;
    }
    
    public static function setNamespace($key, $values) {
        self::$NAMESPACE[$key] = $values;
    }
    
    public static function setDefault($key, $controller = 'Index', $method = 'index', $argument = ['']) {
        self::$DEFAULT[$key] = [
            'controller' => $controller,
            'method'     => $method,
            'argument'   => $argument,
        ];
    }
    
    public static function getDATA() {
        return self::$DATA['data'];
    }
    
    public static function setDATA($key, $value) {
        self::$DATA['data'][$key] = $value;
    }
    
    public function setEvent($route, $callback) {
        $this->events[$route] = $callback;
    }
    
    public function load() {
        $this->event();
        
        $instanceController = $this->getInstanceController();
        $method             = $this->getMethod($instanceController);
        $argument           = $this->getArguments();
        
        $data       = call_user_func_array([
            $instanceController,
            $method,
        ], $argument);
        self::$DATA = array_merge_recursive(self::$DATA, $data);
        
        $this->event('afterCallFunc');
        
        $view = new ViewController(self::$REQUEST, self::$DATA);
        $view->render();
    }
    
    private function event($event = NULL) {
        $keyEvent = $event;
        
        if (empty($event)) {
            if (array_key_exists(self::$REQUEST->getRoute(), $this->events)) {
                $keyEvent = self::$REQUEST->getRoute();
            } elseif (array_key_exists(strtolower(self::$REQUEST->getController()), $this->events)) {
                $keyEvent = strtolower(self::$REQUEST->getController());
            }
        }
        
        if (!empty($keyEvent)) {
            $keysEvent = array_keys($this->events);
            $key       = array_search($keyEvent, $keysEvent);
            
            if ($key !== FALSE) {
                $callback = $this->events[$keysEvent[$key]];
                is_callable($callback) ? $callback() : FALSE;
            }
        }
    }
    
    private function getInstanceController() {
        $key        = $this->getKeyRoutes();
        $controller = self::$REQUEST->getController();
        
        $path      = self::$PATH[$key];
        $namespace = self::$NAMESPACE[$key];
        
        if (empty($controller)) {
            $keyDefault = $key;
            
            if (!array_key_exists($keyDefault, self::$DEFAULT)) {
                $keyDefault = 'default';
            }
            
            $controller = self::$DEFAULT[$keyDefault]['controller'];
        }
        
        self::$REQUEST->setController($controller);
        
        $controller .= 'Controller';
        
        $pathController = $path . $controller . '.php';
        
        if (!file_exists($pathController)) {
            $this->event('error');
        }
        
        $ctrNamespace = $namespace . $controller;
        
        return new $ctrNamespace();
    }
    
    private function getKeyRoutes() {
        $key = array_search(self::$REQUEST->getRoute(), self::$ROUTES, TRUE);
        
        if ($key === FALSE) {
            $key = 'default';
        }
        
        return $key;
    }
    
    private function getMethod($instanceController) {
        $method = self::$REQUEST->getMethod();
        
        if (!method_exists($instanceController, self::$REQUEST->getMethod())) {
            
            $key = $this->getKeyRoutes();
            
            if (!array_key_exists($key, self::$DEFAULT)) {
                $key = 'default';
            }
            
            $method = self::$DEFAULT[$key]['method'];
            self::$REQUEST->setMethod($method);
        }
        
        return $method;
    }
    
    private function getArguments() {
        $arguments = self::$REQUEST->getArgs();
        
        if (empty($arguments)) {
            $key = $this->getKeyRoutes();
            
            if (!array_key_exists($key, self::$DEFAULT)) {
                $key = 'default';
            }
            
            $arguments = self::$DEFAULT[$key]['arguments'];
            self::$REQUEST->setArgs($arguments);
        }
        
        return $arguments;
    }
    
}
