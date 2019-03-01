<?php

namespace App\Controllers\Api\Dashboard;

use App\Facades\ModelFacade;
use App\Facades\Utils;
use App\Models\Users;
use Silver\Core\Bootstrap\Facades\Request;
use Silver\Core\Controller;

/**
 * UsersApi controller
 */
class UsersApiController extends Controller {
    
    public function get($id) {
        if ($id) {
            return $this->getUserById($id);
        }
        
        $userModel = ModelFacade::model(Users::class)
                                ->search()
                                ->pagination()
                                ->sort();
        
        return [
                'users'      => $userModel->all(),
                'pagination' => $userModel->getPagination(),
        ];
    }
    
    /**
     * @param $id
     *
     * @return Users
     */
    private function getUserById($id) {
        if ($user = Users::find($id)) {
            return $user;
        }
        
        throw new \RuntimeException("Usuario desconocido.");
    }
    
    public function post() {
        $user                  = new Users();
        $user->user_password   = Request::input('user_password'); //TODO: cifrar.
        $user->user_registered = Utils::dateNow();
        
        return $this->saveUser($user);
    }
    
    /**
     * @param Users $user
     *
     * @return Users
     */
    function saveUser($user) {
        $user->user_login = Request::input('user_login');
        $user->user_name  = Request::input('user_name', $user->user_login);
        $user->user_email = Request::input('user_email');
        
        return $user->save();
    }
    
    public function put() {
        return $this->saveUser($this->getUserById(Request::input('id')));
    }
    
    public function delete() {
        $user = $this->getUserById(Request::input('id'));
        $user->delete();
    }
}
