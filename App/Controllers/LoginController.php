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
        return View::make('login.index');
    }
    
    public function form() {
        $redirect = URL;
        
        if (Auth::login()) {
            $redirect .= '/dashboard';
        } else {
            $redirect .= '/login';
        }
        
        Redirect::to($redirect);
    }
}
