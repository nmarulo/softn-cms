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
    
    public function index() {
        $result     = RequestApiFacade::makeGetRequest(Request::all(), 'dashboard/users');
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
            $message       = empty($id) ? 'Usuario creado correctamente' : 'Usuario actualizado correctamente.';
            $request       = Request::all();
            $request['id'] = $id;
            $user          = RequestApiFacade::makePostRequest($request, 'dashboard/users');
            $user          = ModelFacade::arrayToObject($user, Users::class);
            Messages::addSuccess($message);
            
            if (empty($id)) {
                Redirect::to(sprintf('%1$s/dashboard/users/form/%2$s', URL, $user->id));
            }
        } elseif ($id) {
            $user = RequestApiFacade::makeGetRequest('', 'dashboard/users', $id);
            //TODO: comprobar el código de respuesta http
            $user = ModelFacade::arrayToObject($user, Users::class);
        } else {
            $user = new Users();
        }
        
        return View::make('dashboard.users.form')
                   ->with('user', $user);
    }
    
    public function delete() {
        //TODO: petición delete.
        $id = Request::input('id');
        
        if ($user = Users::find($id)) {
            $user->delete();
            Messages::addSuccess('Usuario borrado correctamente.');
        } else {
            Messages::addDanger('El usuario no existe.');
        }
    }
}
