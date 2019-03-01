<?php

namespace App\Controllers\Api;

use App\Controllers\Api\Dashboard\UsersApiController;
use App\Models\Users;
use Silver\Core\Bootstrap\Facades\Request;
use Silver\Core\Controller;

/**
 * RegisterApi controller
 */
class RegisterApiController extends Controller {
    
    public function register() {
        $request = Request::all();
        $user    = Users::where('user_login', '=', $request['user_login'])
                        ->orWhere('user_email', '=', $request['user_email'])
                        ->first();
        
        if ($user) {
            throw new \RuntimeException('No puedes registrar este usuario.');
        }
        
        if ($request['user_password'] != $request['user_password_re']) {
            throw new \RuntimeException('Las contraseñas no son iguales.');
        }
        
        return (new UsersApiController())->post();
    }
    
}
