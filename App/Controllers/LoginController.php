<?php

namespace App\Controllers;

use App\Facades\Messages;
use App\Facades\Rest\LoginRestFacade;
use App\Rest\Request\UserRequest;
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
        $request  = UserRequest::parseOf(Request::all());
        $response = LoginRestFacade::login($request);
        $redirect = URL;
        
        if ($response) {
            Messages::addSuccess('Inicio de sesión correcto.');
            Session::set('user_login', $response->userLogin);
            $redirect .= '/dashboard';
        } else {
            $redirect .= '/login';
        }
        
        Redirect::to($redirect);
    }
}
