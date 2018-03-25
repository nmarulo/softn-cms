<?php

namespace App\Helpers;

use App\Models\Users;
use Silver\Core\Bootstrap\Facades\Request;
use Silver\Http\Session;
use App\Facades\Token;

/**
 * auth Helper
 */
class Auth {
    
    public function basic() {
        if (Token::check(Request::input('jwt_token'))) {
            $userLogin    = Request::input('user_login');
            $userPassword = Request::input('user_password');
            $user         = Users::query()
                                 ->where('user_login', '=', $userLogin)
                                 ->first();
            
            if ($user && $user->user_password == $userPassword) {
                Session::set('session_user', $user->id);
                
                return TRUE;
            }
        }
        
        return FALSE;
    }
    
    public function session() {
        return Session::exists('session_user');
    }
    
}
