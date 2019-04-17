<?php

namespace App\Controllers;

use App\Facades\MessagesFacade;
use Silver\Core\Controller;
use Silver\Http\Redirect;
use Silver\Http\Session;

/**
 * logout controller
 */
class LogoutController extends Controller {
    
    public function index() {
        Session::delete('user_login');
        Session::delete('token');
        MessagesFacade::addSuccess('Sesión finalizada correctamente.');
        Redirect::to(URL . '/login');
    }
    
}
