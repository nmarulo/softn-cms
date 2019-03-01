<?php
/**
 * LoginMiddleware.php
 */

namespace App\Middlewares;

use Closure;
use Silver\Http\Redirect;
use Silver\Http\Request;
use Silver\Http\Response;
use Silver\Http\Session;

/**
 * Class LoginMiddleware
 * @author Nicolás Marulanda P.
 */
class LoginMiddleware {
    
    public function execute(Request $request, Response $response, Closure $next) {
        $route = $request->route();
        //El route es null cuando la dirección no existe. Mostrara la pagina de error 404.
        if ($route == NULL) {
            return $next();
        }
    
        $middleware = $route->middleware();
        
        if ($middleware != 'login') {
            return $next();
        }
        
        //Si esta intentado acceder al panel de control y no ha iniciado sesión.
        if (Session::exists('user_login')) {
            Redirect::to(URL . '/dashboard');
        }
        
        //Si es uno publico continua
        return $next();
    }
}
