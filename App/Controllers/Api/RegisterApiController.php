<?php

namespace App\Controllers\Api;

use App\Controllers\Api\Dashboard\UsersApiController;
use App\Facades\Api\ResponseApiFacade;
use App\Facades\Utils;
use App\Models\Users;
use Silver\Core\Controller;

/**
 * RegisterApi controller
 */
class RegisterApiController extends Controller {
    
    public function register() {
        return ResponseApiFacade::makeResponse(function($request) {
            $user = Users::where('user_login', '=', $request['user_login'])
                         ->orWhere('user_email', '=', $request['user_email'])
                         ->first();
            
            if ($user) {
                throw new \RuntimeException('No puedes registrar este usuario.');
            }
            
            if ($request['user_password'] != $request['user_password_re']) {
                throw new \RuntimeException('Las contraseñas no son iguales.');
            }
            
            $usersApiController    = new UsersApiController();
            $user                  = new Users();
            $user->user_password   = $request['user_password']; //TODO: cifrar.
            $request['user_name']  = $request['user_login'];
            $user->user_registered = Utils::dateNow();
            
            return $usersApiController->saveUser($request, $user);
        });
    }
    
}
