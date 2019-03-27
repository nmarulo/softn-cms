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
    
    const COMPARISION_TABLE = [
            'id'             => 'id',
            'userLogin'      => 'user_login',
            'userEmail'      => 'user_email',
            'userName'       => 'user_name',
            'userRegistered' => 'user_registered',
            'userPassword'   => 'user_password',
    ];
    
    public function get($id) {
        $userResponse = new UserResponse();
        
        if ($id) {
            $userResponse->users = [
                    $this->userModelToDTO($this->getUserById($id)),
            ];
            
            return $userResponse->toArray();
        }
        
        //TODO: Hasta que no encuentre una forma de capturar la instancia del controlador desde el middleware tendré que seguir usando la clase "Request".
        $request = Utils::parseOf(Request::all(), UserRequest::class);
        
        $userModel = ModelFacade::model(Users::class, $request->dataTable)
                                ->search()
                                ->pagination()
                                ->sort();
        
        $users    = $userModel->all();
        $usersDTO = array_map(function(Users $user) {
            return $this->userModelToDTO($user);
        }, $users);
        
        $userResponse->users      = $usersDTO;
        $userResponse->pagination = $userModel->getPagination();
        
        return $userResponse->toArray();
    }
    
    public function post() {
        return $this->saveUser();
    }
    
    /**
     * @param Users $user
     *
     * @return Users
     */
    function saveUser(?Users $user = NULL): array {
        $response = new UserResponse();
        $request  = Utils::parseOf(Request::all(), UserRequest::class);
        
        if (is_null($user)) {
            $request->userRegistered = Utils::dateNow();
        } else {
            $request->id = $user->id;
        }
        
        $user            = $this->userDtoToModel($request)
                                ->save();
        $response->users = [
                $this->userModelToDTO($user),
        ];
        
        return $response->toArray();
    }
    
    public function put($id) {
        return $this->saveUser($this->getUserById($id));
    }
    
    public function delete() {
        $user = $this->getUserById(Request::input('id'));
        $user->delete();
    }
    
    private function userModelToDTO(Users $user): \App\Rest\Dto\Users {
        $userDTO            = new \App\Rest\Dto\Users();
        $propertyNamesModel = array_keys($user->data());
        
        foreach (self::COMPARISION_TABLE as $propDto => $propModel) {
            if (array_search($propModel, $propertyNamesModel, TRUE) !== FALSE) {
                $userDTO->$propDto = $user->$propModel;
            }
        }
        
        return $userDTO;
    }
    
    private function userDtoToModel(\App\Rest\Dto\Users $userDTO): Users {
        $user               = new Users();
        $propertyNamesModel = array_keys($userDTO->getProperties());
        
        foreach (self::COMPARISION_TABLE as $propDto => $propModel) {
            if (array_search($propDto, $propertyNamesModel, TRUE) !== FALSE) {
                $user->$propModel = $userDTO->$propDto;
            }
        }
        
        return $user;
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
