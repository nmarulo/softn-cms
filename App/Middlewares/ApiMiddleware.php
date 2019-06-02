<?php
/**
 * ApiMiddleware.php
 */

namespace App\Middlewares;

use App\Facades\Api\ResponseApiFacade;
use App\Facades\TokenFacade;
use App\Helpers\ConstHelper;
use Closure;
use Lcobucci\JWT\Builder;
use Silver\Core\Route;
use Silver\Http\Request;
use Silver\Http\Response;

/**
 * Class ApiMiddleware
 * @author Nicolás Marulanda P.
 */
class ApiMiddleware {
    
    private $routeName = [
            'login',
            'register',
    ];
    
    public function execute(Request $request, Response $response, Closure $next) {
        $route = $request->route();
        
        //El route es null cuando la dirección no existe. Mostrara la pagina de error 404.
        if ($route == NULL) {
            return $next();
        }
        
        $middleware = $route->middleware();
        
        //En la petición al login no se comprueba el token.
        if ($middleware != 'api') {
            return $next();
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'PUT' || $_SERVER['REQUEST_METHOD'] == 'DELETE') {
            parse_str(file_get_contents("php://input"), $dataInput);
            $_REQUEST = array_merge($_REQUEST, $dataInput);
        }
        
        return $this->makeResponse($request, $route, $next);
    }
    
    private function makeResponse(Request $request, Route $route, Closure $next) {
        if (!$this->checkToken($request, $route)) {
            return ResponseApiFacade::createResponseFormat(401, 'El token no es valido.');
        }
        
        return ResponseApiFacade::makeResponse(function() use ($next) {
            return $next();
        });
    }
    
    private function checkToken(Request $request, $route) {
        if (array_search($route->name(), $this->routeName) === FALSE) {
            $token = ResponseApiFacade::getTokenHeader($request);
            
            if (!TokenFacade::check($token)) {
                header('Content-Type: application/json');
                
                return FALSE;
            }
            
            TokenFacade::generate(function(Builder $builder) use ($token) {
                $userId = TokenFacade::getCustomData($token, ConstHelper::USER_ID_STR);
                $builder->set(ConstHelper::USER_ID_STR, $userId);
                
                return $builder;
            });
        }
        
        return TRUE;
    }
}
