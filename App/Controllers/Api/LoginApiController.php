<?php

namespace App\Controllers\Api;

use App\Facades\Api\RestCallFacade;
use App\Models\Users;
use Silver\Core\Controller;

/**
 * LoginApi controller
 */
class LoginApiController extends Controller {
    
    public function login() {
        return RestCallFacade::makeResponse(function($request) {
            $user = Users::where('user_login', '=', $request['user_login'])
                         ->first();
            
            //Si el usuario existe y su contraseña es igual
            return $user && $user->user_password == $request['user_password'];
        });
    }
    
}
