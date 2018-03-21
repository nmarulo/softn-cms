<?php

namespace App\Controllers;

use Silver\Core\Controller;
use Silver\Http\View;

/**
 * register controller
 */
class RegisterController extends Controller {
    
    public function index() {
        return View::make('register.index');
    }
    
    public function postForm() {
    }
    
}
