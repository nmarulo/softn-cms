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
        RequestApiFacade::makeGetRequest($request, $this->urlUsers);
        
        if (RequestApiFacade::isError()) {
            Messages::addDanger(RequestApiFacade::getMessageError());
        } else {
            $response   = RequestApiFacade::getResponse();
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
        
        if (Utils::isPostRequest()) {
            $message = 'Usuario actualizado correctamente.';
            $request = Request::all();
            unset($request['uri']);
            
            if (empty($id)) {
                $message = 'Usuario creado correctamente';
                RequestApiFacade::makePostRequest($request, $this->urlUsers);
            } else {
                $request['id'] = $id;
                RequestApiFacade::makePutRequest($request, $this->urlUsers);
            }
            
            
            if (RequestApiFacade::isError()) {
                Messages::addDanger(RequestApiFacade::getMessageError());
                $user = ModelFacade::arrayToObject($request, Users::class);
            } else {
                Messages::addSuccess($message);
                $user = ModelFacade::arrayToObject(RequestApiFacade::getResponse(), Users::class);
                
                if (empty($id)) {
                    Redirect::to(sprintf('%1$s/%2$s/form/%3$s', URL, $this->urlUsers, $user->id));
                }
            }
        } elseif ($id) {
            RequestApiFacade::makeGetRequest('', $this->urlUsers, $id);
            
            if (RequestApiFacade::isError()) {
                Messages::addDanger(RequestApiFacade::getMessageError());
            } else {
                $user = ModelFacade::arrayToObject(RequestApiFacade::getResponse(), Users::class);
            }
        }
        
        return View::make('dashboard.users.form')
                   ->with('user', $user);
    }
    
    public function delete() {
        RequestApiFacade::makeDeleteRequest(Request::all(), $this->urlUsers);
        
        if (RequestApiFacade::isError()) {
            Messages::addDanger(RequestApiFacade::getMessageError());
        } else {
            Messages::addSuccess('Usuario borrado correctamente.');
        }
    }
}
