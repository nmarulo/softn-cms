<?php
/**
 * ApiMiddleware.php
 */

namespace App\Middlewares;

use App\Facades\Api\ResponseApiFacade;
use App\Facades\TokenFacade;
use Closure;
use Lcobucci\JWT\Builder;
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
        if ($middleware != 'api' || array_search($route->name(), $this->routeName) !== FALSE) {
            return $next();
        }
        
        $token = ResponseApiFacade::getTokenHeader($request);
        
        if (!TokenFacade::check($token)) {
            header('Content-Type: application/json');
            
            return ResponseApiFacade::createResponseFormat(401, 'El token no es valido.');
        }
        
        TokenFacade::generate(function(Builder $builder) use ($token) {
            $userLogin = TokenFacade::getCustomData($token, 'user_login');
            $builder->set('user_login', $userLogin);
            
            return $builder;
        });
        
        if ($_SERVER['REQUEST_METHOD'] == 'PUT' || $_SERVER['REQUEST_METHOD'] == 'DELETE') {
            parse_str(file_get_contents("php://input"), $dataInput);
            $_REQUEST = array_merge($_REQUEST, $dataInput);
        }
        
        return $next();
    }
}
