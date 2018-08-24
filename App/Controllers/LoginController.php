<?php

namespace App\Controllers;

use App\Facades\Api\RequestApiFacade;
use App\Facades\Messages;
use App\Facades\ModelFacade;
use App\Models\Users;
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
        $redirect            = URL;
        $user                = new Users();
        $user->user_login    = Request::input('user_login');
        $user->user_password = Request::input('user_password');
        RequestApiFacade::makePostRequest($user, 'login');
        
        if (RequestApiFacade::isError()) {
            Messages::addDanger(RequestApiFacade::getMessageError());
            $redirect .= '/login';
        } else {
            $user = ModelFacade::arrayToObject(RequestApiFacade::getResponse(), Users::class);
            Messages::addSuccess('Inicio de sesión correcto.');
            Session::set('user_login', $user->user_login);
            $redirect .= '/dashboard';
        }
        
        Redirect::to($redirect);
    }
}
