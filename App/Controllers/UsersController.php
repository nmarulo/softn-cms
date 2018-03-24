<?php

namespace App\Controllers;

use App\Facades\Utils;
use App\Models\Users;
use Silver\Core\Bootstrap\Facades\Request;
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
    
    public function form($id) {
        if (empty($id)) {
            $user = new Users();
        } else {
            $user = Users::find($id);
            
        }
        
        if (Request::method() == 'post') {
            $user->user_name       = Request::input('user_name');
            $user->user_login      = Request::input('user_login');
            $user->user_email      = Request::input('user_email');
            $user->user_password   = Request::input('user_password');
            $user->user_registered = empty($id) ? Utils::dateNow() : $user->user_registered;
            $user                  = $user->save();
        }
        
        return View::make('dashboard.users.form')
                   ->with('user', $user);
    }
}
