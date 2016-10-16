<?php
/**
 * Help.php
 */

namespace SoftnCMS\Helpers;

use SoftnCMS\controllers\Router;

/**
 * Class Help
 * @author Nicolás Marulanda P.
 */
class Helps {
    
    public static function redirectFunc($concat = '') {
        self::redirectRoute(Router::getRequest()
                                  ->getMethod() . "/$concat");
    }
    
    public static function redirectRoute($concat = '') {
        $request = Router::getRequest();
        self::redirect($request->getRoute() . '/' . strtolower($request->getController()) . "/$concat");
    }
    
    public static function redirect($route = '') {
        header("Location: " . Router::getDATA()[SITE_URL] . $route);
        exit();
    }
    
}
