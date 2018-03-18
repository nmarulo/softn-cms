<?php

namespace App\Controllers;

use Silver\Core\Controller;
use Silver\Http\View;

/**
 * login controller
 */
class LoginController extends Controller {
    
    public function index() {
        return View::make('login.index');
    }
    
    public function postForm() {
    
    }
}
