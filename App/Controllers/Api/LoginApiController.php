<?php

namespace App\Controllers\Api;

use App\Facades\TokenFacade;
use App\Models\Users;
use Lcobucci\JWT\Builder;
use Silver\Core\Bootstrap\Facades\Request;
use Silver\Core\Controller;

/**
 * LoginApi controller
 */
class LoginApiController extends Controller {
    
    public function login() {
        $user = Users::where('user_login', '=', Request::input('user_login'))
                     ->first();
        
        //Si el usuario existe y su contraseña es igual
        if ($user && $this->checkPassword($user)) {
            TokenFacade::generate(function(Builder $builder) use ($user) {
                $builder->set('user_login', $user->user_login);
                
                return $builder;
            });
            
            return $user;
        } else {
            throw new \RuntimeException('Usuario y/o contraseña incorrecto(s).');
        }
    }
    
    private function checkPassword($user) {
        //TODO: cifrar
        return $user->user_password == Request::input('user_password');
    }
    
}
