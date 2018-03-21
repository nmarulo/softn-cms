<?php

namespace App\Controllers;

use App\Models\Users;
use Silver\Core\Controller;
use Silver\Http\View;

/**
 * users controller
 */
class UsersController extends Controller {
    
    public function index() {
        return View::make('dashboard.users.index')
                   ->with('users', Users::all());
    }
    
}
