<?php

namespace App\Controllers;

use App\Facades\Api\RestCallFacade;
use App\Facades\Messages;
use App\Models\Users;
use Silver\Core\Bootstrap\Facades\Request;
use Silver\Core\Controller;
use Silver\Http\Redirect;
use Silver\Http\Session;
use Silver\Http\View;

/**
 * login controller
 */
class LoginController extends Controller {
    
    public function index() {
        return View::make('login.index');
    }
    
    public function form() {
        $redirect            = URL;
        $user                = new Users();
        $user->user_login    = Request::input('user_login');
        $user->user_password = Request::input('user_password');
        $result              = RestCallFacade::makePostRequest($user, 'login');
        
        if ($result) {
            Messages::addSuccess('Inicio de sesión correcto.');
            Session::set('user_login', $user->user_login);
            $redirect .= '/dashboard';
        } else {
            Messages::addDanger('Usuario y/o contraseña incorrecto(s).');
            $redirect .= '/login';
        }
        
        Redirect::to($redirect);
    }
}
