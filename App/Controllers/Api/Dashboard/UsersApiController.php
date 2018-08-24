<?php

namespace App\Controllers\Api\Dashboard;

use App\Facades\Api\ResponseApiFacade;
use App\Facades\ModelFacade;
use App\Facades\Utils;
use App\Models\Users;
use Silver\Core\Controller;

/**
 * UsersApi controller
 */
class UsersApiController extends Controller {
    
    public function get($id) {
        return ResponseApiFacade::makeResponse(function() use ($id) {
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
        });
    }
    
    private function getUserById($id) {
        if ($user = Users::find($id)) {
            return $user;
        }
        
        throw new \RuntimeException("Usuario desconocido.");
    }
    
    public function post() {
        return ResponseApiFacade::makeResponse(function($request) {
            $user                  = new Users();
            $user->user_password   = $request['user_password']; //TODO: cifrar.
            $user->user_registered = Utils::dateNow();
            
            return $this->saveUser($request, $user);
        });
    }
    
    /**
     * @param       $request
     * @param Users $user
     *
     * @return Users
     */
    function saveUser($request, $user) {
        $user->user_name  = $request['user_name'];
        $user->user_login = $request['user_login'];
        $user->user_email = $request['user_email'];
        
        return $user->save();
    }
    
    public function put() {
        return ResponseApiFacade::makeResponse(function($request) {
            return $this->saveUser($request, $this->getUserById($request['id']));
        });
    }
    
    public function delete() {
        return ResponseApiFacade::makeResponse(function($request) {
            $user = $this->getUserById($request['id']);
            $user->delete();
        });
    }
}
