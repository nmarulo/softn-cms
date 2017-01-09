<?php

/**
 * Controlador de la URL.
 */

namespace SoftnCMS\controllers;

use SoftnCMS\Helpers\ArrayHelp;

/**
 * Clase Request para obtener los datos enviados por URL.
 * @author Nicolás Marulanda P.
 */
class Request {
    
    /** @var string Nombre del controlador. */
    private $controller;
    
    /** @var string Nombre del método a ejecutar. */
    private $method;
    
    /** @var array Lista de argumentos. */
    private $args;
    
    /** @var string Nombre de la ruta. */
    private $route;
    
    /** @var string Dirección de la ruta. */
    private $url;
    
    /**
     * Constructor.
     */
    public function __construct() {
        $this->method = 'index';
        //La primera letra debe estar en mayúscula.
        $this->controller = 'Index';
        $this->args       = ['data' => []];
        $this->parseUrl();
    }
    
    /**
     * Método que comprueba la url y obtiene sus datos.
     */
    private function parseUrl() {
        $get = ArrayHelp::get($_GET, URL_GET);
        
        if ($get === FALSE) {
            $this->route = Router::getRoutes()['default'];
            
            return TRUE;
        }
        
        $this->url = trim(Sanitize::url($get), '/');
        /*
         * La URL contiene:
         * [] = carpeta - EJ: en caso de estar en el panel de administración.
         * [] = controlador
         * [] = método
         * [] = argumentos
         */
        $url = \explode('/', $this->url);
        $url = $this->checkUrl($url);
        $this->selectController(\array_shift($url));
        
        //La plantilla, por ahora, siempre llama al método index del controlador.
        if ($this->route == Router::getRoutes()['admin']) {
            $this->selectMethod(\array_shift($url));
        }
        
        $this->selectArgs(\array_shift($url));
    }
    
    /**
     * Método que comprueba la url y obtiene la ruta actual y los argumentos de la ruta adicionales. Retorna la lista
     * con los datos de la url, pero, sin el nombre de la ruta (por ejemplo, si es "admin/post/update/1" retorna
     * "post/update/1") y sin los argumentos adicionales, dejando el nombre del controlador, el nombre del método y el
     * identificador a enviar al método (si es el caso, update o delete).
     *
     * @param array $url Lista con los datos de la URL.
     *
     * @return array
     */
    private function checkUrl($url) {
        $aux   = $url;
        $value = \array_shift($aux);
        //Comprueba si la ruta existe
        $key = array_search($value, Router::getRoutes());
        //Obtengo la ruta
        $this->route = $key === FALSE ? Router::getRoutes()['default'] : Router::getRoutes()[$key];
        
        /*
         * Si el usuario esta en el panel de administración
         * retornara la lista con los datos de la URL sin el primer dato
         * ya que este identifica si esta o no en el panel de administración.
         */
        $url = $this->route == Router::getRoutes()['admin'] ? $aux : $url;
        
        $routeArg = Router::getRoutesARG();
        foreach ($routeArg as $value) {
            $url = $this->routeArg($value, $url);
        }
        
        return $url;
    }
    
    /**
     * Método que extrae los datos extra, a enviar al método del controlador, de la ruta y retorna la url sin estos
     * datos.
     *
     * @param array $url
     *
     * @return array
     */
    private function routeArg($route, $url) {
        $key = \array_search($route, $url);
        
        if ($key === \FALSE) {
            return $url;
        }
        
        //Incremento la posición para obtener el valor de la ruta
        ++$key;
        
        if (\array_key_exists($key, $url)) {
            $this->args['data'][$route] = $url[$key];
            unset($url[$key]);
        }
        unset($url[$key - 1]);
        
        //Luego de eliminar los datos se reinician los valores de los indices.
        return \array_merge($url);
    }
    
    /**
     * Método que establece el nombre del controlador.
     *
     * @param string $url
     */
    private function selectController($url) {
        $url = Sanitize::alphabetic($url);
        if (!empty($url)) {
            /*
             * Para evitar problemas con el nombre del fichero
             * la primera letra debe estar en mayúscula.
             */
            $url = strtoupper(substr($url, 0, 1)) . substr($url, 1);
        }
        $this->controller = $url;
    }
    
    /**
     * Método que establece el método a ejecutar.
     *
     * @param string $url
     */
    private function selectMethod($url) {
        if (!empty($url)) {
            $this->method = Sanitize::alphabetic($url);
        }
    }
    
    /**
     * Método que establece los argumentos enviados.
     *
     * @param array $url
     */
    private function selectArgs($url) {
        $this->args['data']['id'] = Sanitize::integer($url);
    }
    
    /**
     * Método que obtiene la ruta que el usuario ingreso.
     * @return string
     */
    public function getUrl() {
        return $this->url;
    }
    
    /**
     * Método que obtiene el nombre de la ruta. EJ: "admin", "login", "register".
     * @return string
     */
    public function getRoute() {
        return $this->route;
    }
    
    /**
     * Método que establece el nombre de la ruta.
     *
     * @param $route
     */
    public function setRoute($route) {
        $this->route = $route;
    }
    
    /**
     * Método que obtiene el nombre del controlador.
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
     * Método que obtiene el nombre del método.
     * @return string
     */
    public function getMethod() {
        return $this->method;
    }
    
    /**
     * Método que establece el nombre del método.
     *
     * @param $method
     */
    public function setMethod($method) {
        $this->method = $method;
    }
    
    /**
     * Método que obtiene los argumentos.
     * @return array
     */
    public function getArgs() {
        return $this->args;
    }
    
    /**
     * Método que establece los argumentos.
     *
     * @param $args
     */
    public function setArgs($args) {
        $this->args = $args;
    }
    
}
