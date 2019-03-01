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

use App\Facades\Api\RequestApiFacade;
use App\Helpers\Api\ApiHelper;
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
            'login',
    ];
    
    public function execute(Request $request, Response $response, Closure $next) {
        $route = $request->route();
        //El route es null cuando la dirección no existe. Mostrara la pagina de error 404.
        if ($route == NULL) {
            return $next();
        }
        
        $middleware = $route->middleware();
        
        if ($middleware == 'api') {
            return $next();
        }
        
        //Si no encuentra ninguno redirecciona a la pagina de error.
        if (array_search($middleware, $this->unguard) === FALSE) {
            return View::error('404');
        }
        
        //Si esta intentado acceder al panel de control y no ha iniciado sesión.
        if ($middleware == 'dashboard' && !Session::exists('user_login')) {
            Redirect::to(URL . '/logout');
        }
        
        $return = $next();
        
        if (!$request->ajax() && RequestApiFacade::getStatusCode() == ApiHelper::$HTTP_STATUS_UNAUTHORIZED) {
            Redirect::to(URL . '/logout');
        }
        
        //Si es uno publico continua
        return $return;
    }
    
}
