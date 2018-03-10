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
use Silver\Http\Request;
use Silver\Http\Response;

class Auth implements MiddlewareInterface {
    
    // put the name to make it public
    private $unguard = [
        'unguard',
        'guest',
        'public',
    ];
    
    public function execute(Request $request, Response $response, Closure $next) {
        if ($request->route() == NULL) {
            return $next();
        }
        
        if (!array_search($request->route()
                                  ->middleware(), $this->unguard) !== FALSE) {
            //Change here if you want to check if someone is loggin in owherwise use Error 404;
            
            //Redirect::to('/');
            return \Silver\Http\View::error('404');
        }
        
        return $next();
    }
    
}
