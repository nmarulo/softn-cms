<?php

namespace App\Helpers;

use App\Models\Users;
use Silver\Core\Bootstrap\Facades\Request;
use Silver\Http\Session;

/**
 * auth Helper
 */
class Auth {
    
    public function login() {
        $userLogin    = Request::input('user_login');
        $userPassword = Request::input('user_password');
        $user         = Users::query()
                             ->where('user_login', '=', $userLogin)
                             ->first();
        
        //Si el usuario existe y su contraseña es igual
        if ($user && $user->user_password == $userPassword) {
            Session::set('session_user', $user->id);
            
            return TRUE;
        }
        
        return FALSE;
    }
    
    public function session() {
        return Session::exists('session_user');
    }
    
}
