<?php

namespace App\Controllers\Api\Dashboard\Users;

use App\Facades\SearchFacade;
use App\Facades\UtilsFacade;
use App\Models\Users;
use App\Rest\Dto\ProfileDTO;
use App\Rest\Dto\UsersDTO;
use App\Rest\Requests\Users\UserRequest;
use App\Rest\Requests\Users\UsersRequest;
use App\Rest\Responses\Users\UserResponse;
use App\Rest\Responses\Users\UsersResponse;
use Silver\Core\Bootstrap\Facades\Request;
use Silver\Core\Controller;

/**
 * UsersApi controller
 */
class UsersApiController extends Controller {
    
    /**
     * @param $id
     *
     * @return array
     * @throws \Exception
     */
    public function get($id) {
        if ($id) {
            $model    = $this->getUserById($id);
            $response = $this->getResponse($model);
            $this->setProfileResponse($response, $model);
            
            return $response->toArray();
        }
        
        $usersResponse = new UsersResponse();
        
        //TODO: Hasta que no encuentre una forma de capturar la instancia del controlador desde el middleware tendré que seguir usando la clase "Request".
        $request   = UsersRequest::parseOf(Request::all());
        $userModel = SearchFacade::init(Users::class)
                                 ->search($request->users, $request->strict)
                                 ->dataTable($request->dataTable)
                                 ->sort();
        $users     = $userModel->all();
        
        $users = array_map(function(Users $model) {
            $userResponse = $this->getResponse($model);
            $this->setProfileResponse($userResponse, $model);
            
            return $userResponse;
        }, $users);
        
        $usersResponse->users      = $users;
        $usersResponse->pagination = $userModel->getPagination();
        
        return $usersResponse->toArray();
    }
    
    /**
     * @return array
     * @throws \Exception
     */
    public function post() {
        return $this->saveUser();
    }
    
    /**
     * @param $id
     *
     * @return array
     * @throws \Exception
     */
    public function put($id) {
        return $this->saveUser($id);
    }
    
    public function delete($id) {
        $this->getUserById($id)
             ->delete();
    }
    
    /**
     * @param $id
     *
     * @return array
     * @throws \Exception
     */
    public function putPassword($id) {
        $request = UserRequest::parseOf(Request::all());
        $this->checkUpdatePassword($request, Users::find($id));
        
        return $this->saveUser($id);
    }
    
    /**
     * @param int $id
     *
     * @return array
     * @throws \Exception
     */
    private function saveUser(?int $id = NULL): array {
        $request = UserRequest::parseOf(Request::all());
        
        if (is_null($id)) {
            $this->checkPassword($request);
            $request->userRegistered = UtilsFacade::dateNow();
        } else {
            $request->id = $id;
        }
        
        $model    = UsersDTO::convertToModel($request, FALSE);
        $model    = $this->getUserById($model->save()->id);
        $response = $this->getResponse($model);
        $this->setProfileResponse($response, $model);
        
        return $response->toArray();
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
    
    private function checkPassword(UserRequest $request) {
        if ($request->userPassword == $request->userPasswordRe) {
            return;
        }
        
        throw new \RuntimeException("Las contraseñas no son coinciden.");
    }
    
    private function checkUpdatePassword(UserRequest $request, Users $user) {
        if ($request->userCurrentPassword == $user->user_password) {
            $this->checkPassword($request);
            
            return;
        }
        
        throw new \RuntimeException("La contraseña actual es incorrecta.");
    }
    
    /**
     * @param UserResponse $response
     * @param Users        $users
     *
     * @throws \Exception
     */
    private function setProfileResponse(UserResponse &$response, Users $users): void {
        $profileDTO        = ProfileDTO::convertOfModel($users->getProfile());
        $response->profile = $profileDTO;
    }
    
    /**
     * @param Users $users
     *
     * @return UserResponse
     * @throws \Exception
     */
    private function getResponse(Users $users): UserResponse {
        $dto = UsersDTO::convertOfModel($users);
        
        return UserResponse::parseOf($dto->toArray());
    }
}
