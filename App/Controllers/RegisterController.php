<?php

namespace App\Controllers;

use App\Facades\Messages;
use App\Facades\Rest\RegisterRestFacade;
use App\Rest\Request\RegisterUserRequest;
use Silver\Core\Bootstrap\Facades\Request;
use Silver\Core\Controller;
use Silver\Http\Redirect;
use Silver\Http\View;

/**
 * register controller
 */
class RegisterController extends Controller {
    
    public function index() {
        return View::make('register.index');
    }
    
    public function form() {
        $request  = RegisterUserRequest::parseOf(Request::all());
        $response = RegisterRestFacade::register($request);
        $redirect = URL;
        
        if ($response) {
            Messages::addSuccess('Usuario creado correctamente.');
            $redirect .= '/login';
        } else {
            $redirect .= '/register';
        }
        
        Redirect::to($redirect);
    }
    
}
