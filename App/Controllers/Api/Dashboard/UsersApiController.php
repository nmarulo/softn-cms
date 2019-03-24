<?php

namespace App\Controllers\Api\Dashboard;

use App\Facades\ModelFacade;
use App\Facades\Utils;
use App\Models\Users;
use App\Rest\Request\UserRequest;
use App\Rest\Response\UserResponse;
use Silver\Core\Bootstrap\Facades\Request;
use Silver\Core\Controller;

/**
 * UsersApi controller
 */
class UsersApiController extends Controller {
    
    public function get($id) {
        $userResponse = new UserResponse();
        
        if ($id) {
            $userResponse->users = $this->userModelToDTO($this->getUserById($id));
            
            return $userResponse->toArray();
        }
        
        //TODO: Hasta que no encuentre una forma de capturar la instancia del controlador desde el middleware tendré que seguir usando la clase "Request".
        $request = Utils::parseOf(Request::all(), UserRequest::class);
        
        $userModel = ModelFacade::model(Users::class, $request->dataTable)
                                ->search()
                                ->pagination()
                                ->sort();
        
        $users = $userModel->all();
        
        $usersDTO = array_map(function(Users $user) {
            return $this->userModelToDTO($user);
        }, $users);
        
        $userResponse->users      = $usersDTO;
        $userResponse->pagination = $userModel->getPagination();
        
        return $userResponse->toArray();
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
    
    private function userModelToDTO(Users $user): \App\Rest\Dto\Users {
        $userDTO                 = new \App\Rest\Dto\Users();
        $userDTO->id             = $user->id;
        $userDTO->userLogin      = $user->user_login;
        $userDTO->userEmail      = $user->user_email;
        $userDTO->userName       = $user->user_name;
        $userDTO->userRegistered = $user->user_registered;
        
        return $userDTO;
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
}
