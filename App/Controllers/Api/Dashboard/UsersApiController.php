<?php

namespace App\Controllers\Api\Dashboard;

use App\Facades\ModelFacade;
use App\Facades\UtilsFacade;
use App\Models\Users;
use App\Rest\Dto\UsersDTO;
use App\Rest\Request\UserRequest;
use App\Rest\Response\UsersResponse;
use Silver\Core\Bootstrap\Facades\Request;
use Silver\Core\Controller;

/**
 * UsersApi controller
 */
class UsersApiController extends Controller {
    
    public function get($id) {
        $userResponse = new UsersResponse();
        
        if ($id) {
            $model               = $this->getUserById($id);
            $userResponse->users = [
                    UsersDTO::convertOfModel($model),
            ];
            
            return $userResponse->toArray();
        }
        
        //TODO: Hasta que no encuentre una forma de capturar la instancia del controlador desde el middleware tendré que seguir usando la clase "Request".
        $request   = UserRequest::parseOf(Request::all());
        $userModel = ModelFacade::model(Users::class, $request->dataTable)
                                ->search()
                                ->pagination()
                                ->sort();
        
        $users    = $userModel->all();
        $usersDTO = array_map(function(Users $user) {
            return UsersDTO::convertOfModel($user);
        }, $users);
        
        $userResponse->users      = $usersDTO;
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
