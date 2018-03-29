<?php

namespace App\Controllers;

use Silver\Core\Controller;
use Silver\Http\Redirect;
use Silver\Http\Session;
use Silver\Http\View;

/**
 * logout controller
 */
class LogoutController extends Controller {
    
    public function index() {
        Session::delete('session_user');
        Redirect::to(URL . '/login');
    }
    
}
