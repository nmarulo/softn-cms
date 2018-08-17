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
        $result     = RequestApiFacade::makeGetRequest(Request::all(), $this->urlUsers);
        $pagination = Pagination::jsonUnSerialize($result['pagination']);
        $users      = array_map(function($value) {
            return ModelFacade::arrayToObject($value, Users::class);
        }, $result['users']);
        
        return View::make('dashboard.users.index')
                   ->with('users', $users)
                   ->withComponent($pagination, 'pagination');
    }
    
    public function form($id) {
        if (Utils::isPostRequest()) {
            $message = 'Usuario actualizado correctamente.';
            $request = Request::all();
            
            if (empty($id)) {
                $message = 'Usuario creado correctamente';
                $user    = RequestApiFacade::makePostRequest($request, $this->urlUsers);
            } else {
                $request['id'] = $id;
                $user          = RequestApiFacade::makePutRequest($request, $this->urlUsers);
            }
            
            $user = ModelFacade::arrayToObject($user, Users::class);
            Messages::addSuccess($message);
            
            if (empty($id)) {
                Redirect::to(sprintf('%1$s/%2$s/form/%3$s', URL, $this->urlUsers, $user->id));
            }
        } elseif ($id) {
            $user = RequestApiFacade::makeGetRequest('', $this->urlUsers, $id);
            //TODO: comprobar el código de respuesta http
            $user = ModelFacade::arrayToObject($user, Users::class);
        } else {
            $user = new Users();
        }
        
        return View::make('dashboard.users.form')
                   ->with('user', $user);
    }
    
    public function delete() {
        if (RequestApiFacade::makeDeleteRequest(Request::all(), $this->urlUsers)) {
            Messages::addSuccess('Usuario borrado correctamente.');
        } else {
            Messages::addDanger('El usuario no existe.');
        }
    }
}
