<?php

namespace App\Controllers\Dashboard;

use App\Facades\Messages;
use App\Facades\ModelFacade;
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
        $query = NULL;
        
        if (Request::ajax()) {
            $filterStart = Request::input('filter-start');
            $filterFinal = Request::input('filter-final');
            $query       = Users::query()
                                ->where('user_registered', '>=', $filterStart)
                                ->where('user_registered', '<=', $filterFinal);
            //TODO: no puedo buscar y filtrar a la vez ya que no esta funcionando el "where" con paréntesis.
        }
        
        $userModel = ModelFacade::model(Users::class, $query)
                                ->search()
                                ->pagination()
                                ->sort();
        $users     = $userModel->all();
        
        return View::make('dashboard.users.index')
                   ->with('users', $users)
                   ->withComponent($userModel->getPagination(), 'pagination');
    }
    
    public function form($id) {
        if (empty($id)) {
            $user    = new Users();
            $message = 'Usuario creado correctamente';
        } else {
            $user    = Users::find($id);
            $message = 'Usuario actualizado correctamente.';
        }
        
        if (Utils::isRequestMethod('post')) {
            $user->user_name  = Request::input('user_name');
            $user->user_login = Request::input('user_login');
            $user->user_email = Request::input('user_email');
            //TODO: cifrar.
            $user->user_password   = Request::input('user_password');
            $user->user_registered = empty($id) ? Utils::dateNow() : $user->user_registered;
            $user                  = $user->save();
            Messages::addSuccess($message);
            
            if (empty($id)) {
                Redirect::to(sprintf('%1$s/dashboard/users/form/%2$s', URL, $user->id));
            }
        }
        
        return View::make('dashboard.users.form')
                   ->with('user', $user);
    }
    
    public function delete() {
        $id = Request::input('id');
        
        if ($user = Users::find($id)) {
            $user->delete();
            Messages::addSuccess('Usuario borrado correctamente.');
        } else {
            Messages::addDanger('El usuario no existe.');
        }
    }
}
