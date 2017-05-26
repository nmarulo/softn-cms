<?php

/**
 * Controlador de la URL.
 */

namespace SoftnCMS\controllers;

use SoftnCMS\helpers\ArrayHelp;

/**
 * Clase Request para obtener los datos enviados por URL.
 * @author Nicolás Marulanda P.
 */
class Request {
    
    /** @var array Lista de argumentos. */
    private $args;
    
    /** @var string Nombre del controlador. */
    private $controller;
    
    /** @var string Nombre del método a ejecutar. */
    private $method;
    
    /** @var string Nombre de la ruta. */
    private $route;
    
    /** @var string Dirección url de la pagina. */
    private $url;
    
    /** @var string Dirección url actual. */
    private $urlCurrent;
    
    /**
     * Constructor.
     */
    public function __construct() {
        $this->method = 'index';
        //La primera letra debe estar en mayúscula.
        $this->controller = 'Index';
        $this->args       = ['data' => []];
        $this->initUrl();
        $this->parseUrl();
    }
    
    /**
     * Método que establece la url actual.
     */
    private function initUrl() {
        $host       = $_SERVER['HTTP_HOST'];// localhost
        $scheme     = $_SERVER['REQUEST_SCHEME'];// http
        $uriCurrent = $_SERVER['REQUEST_URI'];// SoftN-CMS/user/1
        $url        = $scheme . '://' . $host;
        $get        = ArrayHelp::get($_GET, URL_GET);
        $uri        = $uriCurrent;
        
        //Con esto logro obtener la(s) carpeta(s) donde este instalada la aplicación.
        if ($get !== FALSE) {
            $strPos = strpos($uriCurrent, $get);
            //Para obtener la uri raíz de la pagina.
            $uri = substr($uriCurrent, 0, $strPos);
        }
        
        $this->urlCurrent = Sanitize::url($url . $uriCurrent);
        $this->url        = Sanitize::url($url . $uri);
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
        
        $url = Sanitize::url($get);
        /*
         * La URL contiene:
         * [] = carpeta - EJ: en caso de estar en el panel de administración.
         * [] = controlador
         * [] = método
         * [] = argumentos
         */
        $url = explode('/', $url);
        $url = $this->checkUrl($url);
        $this->selectController(array_shift($url));
        
        //La plantilla, por ahora, siempre llama al método index del controlador.
        if ($this->route == Router::getRoutes()['admin']) {
            $this->selectMethod(array_shift($url));
        }
        
        $this->selectArgs(array_shift($url));
        
        return TRUE;
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
        $value = array_shift($aux);
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
     * @param string $argName
     * @param array  $url
     *
     * @return array
     */
    private function routeArg($argName, $url) {
        $key = array_search($argName, $url);
        
        if ($key === FALSE) {
            return $url;
        }
        
        //Incremento la posición para obtener el valor de la ruta
        ++$key;
        
        if (array_key_exists($key, $url)) {
            $this->args['data'][$argName] = $url[$key];
            unset($url[$key]);
        }
        unset($url[$key - 1]);
        
        //Luego de eliminar los datos se reinician los valores de los indices.
        return array_merge($url);
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
    
    /**
     * Método que obtiene el nombre del controlador.
     * @return string
     */
    public function getController() {
        return $this->controller;
    }
    
    /**
     * Método que establece el nombre del controlador.
     *
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
     * Método que obtiene la url de la pagina.
     * @return string
     */
    public function getUrl() {
        return $this->url;
    }
    
    /**
     * Método que obtiene la url actual.
     * @return string
     */
    public function getUrlCurrent() {
        return $this->urlCurrent;
    }
    
}
