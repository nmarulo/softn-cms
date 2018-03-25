<?php

namespace App\Controllers;

use App\Facades\Auth;
use App\Facades\Token;
use Silver\Core\Controller;
use Silver\Http\Redirect;
use Silver\Http\View;

/**
 * login controller
 */
class LoginController extends Controller {
    
    public function index() {
        Token::generate();
        
        return View::make('login.index')
                   ->with('token', Token::getToken());
    }
    
    public function form() {
        $redirect = URL;
        
        if (Auth::basic()) {
            $redirect .= '/dashboard';
        } else {
            $redirect .= '/login';
        }
        
        Redirect::to($redirect);
    }
}
