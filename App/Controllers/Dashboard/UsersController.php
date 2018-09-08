<?php

namespace App\Controllers\Dashboard;

use App\Facades\Api\RequestApiFacade;
use App\Facades\Messages;
use App\Facades\ModelFacade;
use App\Facades\Pagination;
use App\Facades\Utils;
use App\Models\Users;
use Silver\Core\Bootstrap\Facades\Request;
use Silver\Core\Controller;
use Silver\Http\Redirect;
use Silver\Http\View;

/**
 * users controller
 */
class UsersController extends Controller {
    
    private $urlUsers = 'dashboard/users';
    
    public function index() {
        $pagination = NULL;
        $users      = [];
        $request    = Request::all();
        //TODO: ERROR: solo al enviar, por get, "'uri' => '/dashboard/users'", por eso lo elimino, en caso de enviarlo.
        unset($request['uri']);
        RequestApiFacade::get($this->urlUsers, $request);
        
        if (RequestApiFacade::isError()) {
            Messages::addDanger(RequestApiFacade::getMessage());
        } else {
            $response   = RequestApiFacade::responseJsonDecode();
            $pagination = Pagination::arrayToObject($response['pagination']);
            $users      = array_map(function($value) {
                return ModelFacade::arrayToObject($value, Users::class);
            }, $response['users']);
        }
        
        return View::make('dashboard.users.index')
                   ->with('users', $users)
                   ->withComponent($pagination, 'pagination');
    }
    
    public function form($id) {
        $user = new Users();
        
        if (RequestApiFacade::isPostRequest()) {
            $message = 'Usuario actualizado correctamente.';
            $request = Request::all();
            unset($request['uri']);
            
            if (empty($id)) {
                $message = 'Usuario creado correctamente';
                RequestApiFacade::post($this->urlUsers, $request);
            } else {
                $request['id'] = $id;
                RequestApiFacade::put($this->urlUsers, $request);
            }
            
            if (RequestApiFacade::isError()) {
                Messages::addDanger(RequestApiFacade::getMessage());
                $user = ModelFacade::arrayToObject($request, Users::class);
            } else {
                Messages::addSuccess($message);
                $user = ModelFacade::arrayToObject(RequestApiFacade::responseJsonDecode(), Users::class);
                
                if (empty($id)) {
                    Redirect::to(sprintf('%1$s/%2$s/form/%3$s', URL, $this->urlUsers, $user->id));
                }
            }
        } elseif ($id) {
            RequestApiFacade::get($this->urlUsers, $id);
            
            if (RequestApiFacade::isError()) {
                Messages::addDanger(RequestApiFacade::getMessage());
            } else {
                $user = ModelFacade::arrayToObject(RequestApiFacade::responseJsonDecode(), Users::class);
            }
        }
        
        return View::make('dashboard.users.form')
                   ->with('user', $user);
    }
    
    public function delete() {
        RequestApiFacade::delete($this->urlUsers, Request::all());
        
        if (RequestApiFacade::isError()) {
            Messages::addDanger(RequestApiFacade::getMessage());
        } else {
            Messages::addSuccess('Usuario borrado correctamente.');
        }
    }
}
