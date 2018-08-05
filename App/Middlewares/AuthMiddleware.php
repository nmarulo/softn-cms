<?php

/**
 * SilverEngine  - PHP MVC framework
 * @package   SilverEngine
 * @author    SilverEngine Team
 * @copyright 2015-2017
 * @license   MIT
 * @link      https://github.com/SilverEngine/Framework
 */

namespace App\Middlewares;

use Closure;
use Silver\Core\Blueprints\MiddlewareInterface;
use Silver\Http\Redirect;
use Silver\Http\Request;
use Silver\Http\Response;
use Silver\Http\Session;
use Silver\Http\View;

class AuthMiddleware implements MiddlewareInterface {
    
    // put the name to make it public
    private $unguard = [
            'unguard',
            'guest',
            'public',
            'dashboard',
    ];
    
    public function execute(Request $request, Response $response, Closure $next) {
        //El route es null cuando la dirección no existe. Mostrara la pagina de error 404.
        if ($request->route() == NULL) {
            return $next();
        }
        
        $middleware = $request->route()
                              ->middleware();
        
        if ($middleware == 'api') {
            return $next();
        }
        
        //Si no encuentra ninguno redirecciona a la pagina de error.
        if (array_search($middleware, $this->unguard) === FALSE) {
            return View::error('404');
        }
        
        //Si esta intentado acceder al panel de control y no ha iniciado sesión.
        if ($middleware == 'dashboard' && !Session::exists('user_login')) {
            Redirect::to(URL . '/login');
        }
        
        //Si es uno publico continua
        return $next();
    }
    
}
