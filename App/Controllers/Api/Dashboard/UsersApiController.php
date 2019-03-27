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
            $model = $this->getUserById($id);
            $userResponse->users = [
                    Utils::castModelToDto(self::COMPARISION_TABLE, $model, \App\Rest\Dto\Users::class),
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
            return Utils::castModelToDto(self::COMPARISION_TABLE, $user, \App\Rest\Dto\Users::class);
        }, $users);
        
        $userResponse->users      = $usersDTO;
        $userResponse->pagination = $userModel->getPagination();
        
        return $userResponse->toArray();
    }
    
    public function post() {
        return $this->saveUser();
    }
    
    /**
     * @param int $id
     *
     * @return array
     */
    private function saveUser(?int $id = NULL): array {
        $response = new UserResponse();
        $request  = Utils::parseOf(Request::all(), UserRequest::class);
        
        if (is_null($id)) {
            $request->userRegistered = Utils::dateNow();
        } else {
            $request->id = $id;
        }
        
        $model           = Utils::castDtoToModel(self::COMPARISION_TABLE, $request, Users::class, FALSE);
        $user            = $this->getUserById($model->save()->id);
        $response->users = [
                Utils::castModelToDto(self::COMPARISION_TABLE, $user, \App\Rest\Dto\Users::class),
        ];
        
        return $response->toArray();
    }
    
    public function put($id) {
        return $this->saveUser($id);
    }
    
    public function delete() {
        $user = $this->getUserById(Request::input('id'));
        $user->delete();
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
