<?php

/**
 * Modulo: Operaciones comunes.
 */

namespace SoftnCMS\helpers;

use SoftnCMS\controllers\Router;

/**
 * Clase Help
 * @author Nicolás Marulanda P.
 */
class Helps {
    
    /**
     * Método que obtiene la fecha actual.
     *
     * @param string $format Formato de la fecha.
     *
     * @return false|string
     */
    public static function dateNow($format = 'Y-m-d H:i:s') {
        return date($format, time());
    }
    
    /**
     * Método para redireccionar a la pagina del controlador actual y su método.
     *
     * @param string $concat
     */
    public static function redirectFunc($concat = '') {
        $method = Router::getRequest()
                        ->getMethod();
        $method = $method == 'index' ? '' : "$method/";
        
        self::redirectRoute($method . $concat);
    }
    
    /**
     * Método para redireccionar a la pagina del controlador actual.
     *
     * @param string $concat
     */
    public static function redirectRoute($concat = '') {
        $request    = Router::getRequest();
        $route      = $request->getRoute();
        $controller = strtolower($request->getController());
        $route      = empty($route) ? '' : "$route/";
        $controller = $controller == 'index' ? '' : "$controller/";
        self::redirect($route . $controller . $concat);
    }
    
    /**
     * Método para redireccionar a la pagina de inicio.
     *
     * @param string $route
     */
    public static function redirect($route = '') {
        header('Location: ' . Router::getDATA()[SITE_URL] . $route);
        exit();
    }
    
}
