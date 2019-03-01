<?php

namespace App\Controllers;

use App\Facades\Api\RequestApiFacade;
use App\Facades\Messages;
use App\Facades\ModelFacade;
use App\Helpers\EMailerHelper;
use App\Models\Users;
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
            $user                = ModelFacade::arrayToObject(RequestApiFacade::responseJsonDecode(), Users::class);
            $user->user_password = Request::input('user_password');
            
            try {
                EMailerHelper::register($user)
                             ->send('Registro de usuario');
            } catch (\Exception $exception) {
                //TODO: log
            }
            
            $redirect .= '/login';
        }
        
        Redirect::to($redirect);
    }
    
}
