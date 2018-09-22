<?php

namespace App\Controllers;

use App\Facades\Api\RequestApiFacade;
use App\Facades\Messages;
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
        $redirect = URL;
        RequestApiFacade::post('register', Request::all());
        
        if (RequestApiFacade::isError()) {
            Messages::addDanger(RequestApiFacade::getMessage());
            $redirect .= '/register';
        } else {
            Messages::addSuccess('Usuario creado correctamente.');
            $redirect .= '/login';
        }
        
        Redirect::to($redirect);
    }
    
}
