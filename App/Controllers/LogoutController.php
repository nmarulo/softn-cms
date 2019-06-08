<?php

namespace App\Controllers;

use App\Facades\MessagesFacade;
use App\Facades\SessionFacade;
use Silver\Core\Controller;
use Silver\Http\Redirect;
use Silver\Http\Session;

/**
 * logout controller
 */
class LogoutController extends Controller {
    
    public function index() {
        SessionFacade::delete();
        MessagesFacade::addSuccess('Sesión finalizada correctamente.');
        Redirect::to(URL . '/login');
    }
    
}
