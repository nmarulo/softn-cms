<?php

/**
 * Controlador de la URL.
 */

namespace SoftnCMS\controllers;

/**
 * Clase que obtiene los datos enviatos por URL.
 * @author Nicolás Marulanda P.
 */
class Request {
    
    /** @var string Nombre del controlador. */
    private $controller;
    
    /** @var string Nombre del metodo a ejecutar. */
    private $method;
    
    /** @var array Lista de argumentos. */
    private $args;
    
    /** @var int Pagina actual. */
    private $paged;
    
    /** @var string Nombre de la ruta. */
    private $route;
    
    /** @var string Dirección de la ruta. */
    private $url;
    
    /**
     * Constructor.
     */
    public function __construct() {
        $this->method       = 'index';
        //La primera letra debe estar en mayuscula.
        $this->controller = 'Index';
        $this->args       = ['data' => []];
        $this->paged      = 0;
        $this->parseUrl();
    }
    
    /**
     * Metodo que comprueba la url y obtiene sus datos.
     */
    private function parseUrl() {
        $get = isset($_GET[\URL_GET]) ? $_GET[\URL_GET] : NULL;
        
        if ($get === NULL) {
            $this->route = Router::getRoutes()['default'];
            
            return TRUE;
        }
        
        $this->url = trim(Sanitize::url($get), '/');
        /*
         * La URL contiene:
         * [] = carpeta - en caso de estar en el panel de administración.
         * [] = controlador
         * [] = metodo
         * [] = argumentos
         */
        $url = \explode('/', $this->url);
        $url = $this->checkUrl($url);
        $this->selectController(\array_shift($url));
        
        //La plantilla, por ahora, siempre llama al metodo index del controlador.
        if ($this->route == Router::getRoutes()['admin']) {
            $this->selectMethod(\array_shift($url));
        }
        
        $this->selectArgs(\array_shift($url));
        
        $this->checkArg();
    }
    
    /**
     * Metodo que establece la ruta actual del usuario.
     *
     * @param array $url Lista con los datos de la URL.
     *
     * @return array
     */
    private function checkUrl($url) {
        $aux         = $url;
        $value       = \array_shift($aux);
        $key         = array_search($value, Router::getRoutes());
        $this->route = $key === FALSE ? Router::getRoutes()['default'] : Router::getRoutes()[$key];
        
        /*
         * Si el usuario esta en el panel de administración
         * retornara la lista con los datos de la URL sin el primer dato
         * ya que este identifica si esta o no en el panel de administración.
         */
        $aux = $this->route == Router::getRoutes()['admin'] ? $aux : $url;
        
        return $this->urlPaged($aux);
    }
    
    /**
     * Metodo que establece el numero de la pagina actual.
     *
     * @param array $url
     *
     * @return array
     */
    private function urlPaged($url) {
        $key = \array_search('paged', $url);
        
        if ($key === \FALSE) {
            return $url;
        }
        
        unset($url[$key]);
        $nextKey = $key + 1;
        
        if (\array_key_exists($nextKey, $url)) {
            $this->paged = Sanitize::integer($url[$nextKey]);
            unset($url[$nextKey]);
        }
        
        //Luego de eliminar los datos se reinician los valores de los indices.
        return \array_merge($url);
    }
    
    /**
     * Metodo que establece el nombre del controlador.
     *
     * @param string $url
     */
    private function selectController($url) {
        $url = Sanitize::alphabetic($url);
        if (!empty($url)) {
            /*
             * Para evitar problemas con el nombre del fichero
             * la primera letra debe estar en mayuscula.
             */
            $url = strtoupper(substr($url, 0, 1)) . substr($url, 1);
        }
        $this->controller = $url;
    }
    
    /**
     * Metodo que establece el metodo a ejecutar.
     *
     * @param string $url
     */
    private function selectMethod($url) {
        if (!empty($url)) {
            $this->method = Sanitize::alphabetic($url);
        }
    }
    
    /**
     * Metodo que establece los argumentos enviados.
     *
     * @param array $url
     */
    private function selectArgs($url) {
        $this->args['data']['id'] = Sanitize::integer($url);
    }
    
    /**
     * Metodo que agrega los datos extra a los argumentos
     * que se enviaran al metodo del controlador.
     */
    private function checkArg() {
        $this->args['data']['paged'] = $this->paged;
    }
    
    public function getUrl() {
        return $this->url;
    }
    
    public function getRoute() {
        return $this->route;
    }
    
    public function setRoute($route) {
        $this->route = $route;
    }
    
    public function getPaged() {
        return $this->paged;
    }
    
    /**
     * Metodo que obtiene el nombre del controlador.
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
     * Metodo que obtiene el nombre del metodo.
     * @return string
     */
    public function getMethod() {
        return $this->method;
    }
    
    public function setMethod($method) {
        $this->method = $method;
    }
    
    /**
     * Metodo que obtiene los argumentos.
     * @return array
     */
    public function getArgs() {
        return $this->args;
    }
    
    public function setArgs($args) {
        $this->args = $args;
    }
    
}
