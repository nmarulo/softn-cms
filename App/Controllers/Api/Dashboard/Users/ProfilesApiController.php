<?php
/**
 * softn-cms
 */

namespace App\Controllers\Api\Dashboard\Users;

use App\Facades\SearchFacade;
use App\Models\ProfileModel;
use App\Models\ProfilesPermissionsModel;
use App\Rest\Dto\PermissionDTO;
use App\Rest\Dto\ProfileDTO;
use App\Rest\Requests\Users\ProfileRequest;
use App\Rest\Requests\Users\ProfilesRequest;
use App\Rest\Responses\Users\ProfileResponse;
use App\Rest\Responses\Users\ProfilesResponse;
use Silver\Core\Bootstrap\Facades\Request;
use Silver\Core\Controller;

/**
 * Class ProfilesApiController
 * @author Nicolás Marulanda P.
 */
class ProfilesApiController extends Controller {
    
    /**
     * @param $id
     *
     * @return array
     * @throws \Exception
     */
    public function get($id) {
        if ($id) {
            $model          = $this->getProfileById($id);
            $permissionsDTO = PermissionDTO::convertOfModel($model->getPermissions());
            
            $dto                   = ProfileDTO::convertOfModel($model);
            $response              = ProfileResponse::parseOf($dto->toArray());
            $response->permissions = $permissionsDTO;
            
            return $response->toArray();
        }
        
        $response = new ProfilesResponse();
        $request  = ProfilesRequest::parseOf(Request::all());
        $models   = SearchFacade::init(ProfileModel::class)
                                ->search($request->profiles)
                                ->all();
        
        $profiles = [];
        
        array_walk($models, function(ProfileModel $model) use (&$profiles) {
            $profile                      = ProfileDTO::convertOfModel($model);
            $profileResponse              = ProfileResponse::parseOf($profile->toArray());
            $profileResponse->permissions = PermissionDTO::convertOfModel($model->getPermissions());
            
            $profiles[] = $profileResponse;
        });
        
        $response->profiles = $profiles;
        
        return $response->toArray();
    }
    
    /**
     * @return array
     * @throws \Exception
     */
    public function post() {
        return $this->save();
    }
    
    /**
     * @param $id
     *
     * @return array
     * @throws \Exception
     */
    public function put($id) {
        return $this->save($id);
    }
    
    /**
     * @param $id
     *
     * @throws \Exception
     */
    public function delete($id) {
        return $this->getProfileById($id)
                    ->delete();
    }
    
    /**
     * @param int|null $id
     *
     * @return array
     * @throws \Exception
     */
    private function save(?int $id = NULL): array {
        $request       = ProfileRequest::parseOf(Request::all());
        $permissionsId = [];
        
        if (!is_null($id)) {
            $request->id   = $id;
            $permissionsId = $request->permissionsId;
        }
        
        $model    = ProfileDTO::convertToModel($request, FALSE);
        $model    = $this->getProfileById($model->save()->id);
        $dto      = ProfileDTO::convertOfModel($model);
        $response = ProfileResponse::parseOf($dto->toArray());
        
        $this->updatePermissions($id, $permissionsId);
        $response->permissions = $model->getPermissions();
        
        return $response->toArray();
    }
    
    /**
     * @param $id
     *
     * @return ProfileModel
     * @throws \Exception
     */
    private function getProfileById($id): ProfileModel {
        if ($model = ProfileModel::find($id)) {
            return $model;
        }
        
        throw new \Exception("Perfil desconocido.");
    }
    
    private function updatePermissions(?int $id, ?array $permissionsId): void {
        if (is_null($id) || empty($permissionsId)) {
            return;
        }
        
        ProfilesPermissionsModel::deleteProfiles($id);
        $model             = new ProfilesPermissionsModel();
        $model->profile_id = $id;
        
        foreach ($permissionsId as $id) {
            $model->permission_id = $id;
            $model->saveNew();
        }
    }
    
}
