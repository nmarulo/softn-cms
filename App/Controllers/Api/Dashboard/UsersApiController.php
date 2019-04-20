<?php

namespace App\Controllers\Api\Dashboard;

use App\Facades\SearchFacade;
use App\Facades\UtilsFacade;
use App\Models\Users;
use App\Rest\Dto\UsersDTO;
use App\Rest\Requests\UserRequest;
use App\Rest\Requests\UsersRequest;
use App\Rest\Responses\UserResponse;
use App\Rest\Responses\UsersResponse;
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
        $userResponse = new UsersResponse();
        
        if ($id) {
            $dto = UsersDTO::convertOfModel($this->getUserById($id));
            
            return UserResponse::parseOf($dto->toArray())
                               ->toArray();
        }
        
        //TODO: Hasta que no encuentre una forma de capturar la instancia del controlador desde el middleware tendré que seguir usando la clase "Request".
        $request   = UsersRequest::parseOf(Request::all());
        $userModel = SearchFacade::init(Users::class)
                                 ->search($request->users, $request->strict)
                                 ->dataTable($request->dataTable)
                                 ->sort();
        
        $userResponse->users      = UsersDTO::convertOfModel($userModel->all());
        $userResponse->pagination = $userModel->getPagination();
        
        return $userResponse->toArray();
    }
    
    public function post() {
        return $this->saveUser();
    }
    
    public function put($id) {
        return $this->saveUser($id);
    }
    
    public function delete($id) {
        $this->getUserById($id)
             ->delete();
    }
    
    /**
     * @param int $id
     *
     * @return array
     * @throws \Exception
     */
    private function saveUser(?int $id = NULL): array {
        $response = new UsersResponse();
        $request  = UserRequest::parseOf(Request::all());
        
        if (is_null($id)) {
            $request->userRegistered = UtilsFacade::dateNow();
        } else {
            $request->id = $id;
        }
        
        $model           = UsersDTO::convertToModel($request, FALSE);
        $user            = $this->getUserById($model->save()->id);
        $response->users = [
                UsersDTO::convertOfModel($user),
        ];
        
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
}
