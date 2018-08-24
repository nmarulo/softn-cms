<?php

namespace App\Controllers\Api;

use App\Facades\Api\ResponseApiFacade;
use App\Facades\TokenFacade;
use App\Models\Users;
use Lcobucci\JWT\Builder;
use Silver\Core\Controller;

/**
 * LoginApi controller
 */
class LoginApiController extends Controller {
    
    public function login() {
        return ResponseApiFacade::makeResponse(function($request) {
            $user = Users::where('user_login', '=', $request['user_login'])
                         ->first();
            
            //Si el usuario existe y su contraseña es igual
            if ($user && $this->checkPassword($user, $request)) {
                TokenFacade::generate(function(Builder $builder) use ($user) {
                    $builder->set('user_login', $user->user_login);
                    
                    return $builder;
                });
                
                return $user;
            } else {
                throw new \RuntimeException('Usuario y/o contraseña incorrecto(s).');
            }
        });
    }
    
    private function checkPassword($user, $request) {
        //TODO: cifrar
        return $user->user_password == $request['user_password'];
    }
    
}
