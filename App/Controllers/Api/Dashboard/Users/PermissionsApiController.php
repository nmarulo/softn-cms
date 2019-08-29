<?php
/**
 * softn-cms
 */

namespace App\Controllers\Api\Dashboard\Users;

use App\Facades\SearchFacade;
use App\Models\PermissionModel;
use App\Rest\Dto\PermissionDTO;
use App\Rest\Requests\Users\PermissionRequest;
use App\Rest\Requests\Users\PermissionsRequest;
use App\Rest\Responses\Users\PermissionResponse;
use App\Rest\Responses\Users\PermissionsResponse;
use Silver\Core\Bootstrap\Facades\Request;
use Silver\Core\Controller;
use Silver\Database\Model;

/**
 * Class PermissionsApiController
 * @author Nicolás Marulanda P.
 */
class PermissionsApiController extends Controller {
    
    /**
     * @param $id
     *
     * @return array
     * @throws \Exception
     */
    public function get($id) {
        if ($id) {
            $dto = PermissionDTO::convertOfModel($this->getPermissionById($id));
            
            return PermissionResponse::parseOf($dto->toArray())
                                     ->toArray();
        }
        
        $response         = new PermissionsResponse();
        $request          = PermissionsRequest::parseOf(Request::all());
        $models           = SearchFacade::init(PermissionModel::class)
                                        ->search($request->permissions)
                                        ->dataTable($request->dataTable)
                                        ->sort();
        $permissionModels = $models->all();
        
        $response->permissions = PermissionDTO::convertOfModel($permissionModels);
        $response->pagination = $models->getPagination();
        
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
    
    public function delete($id) {
        return $this->getPermissionById($id)
                    ->delete();
    }
    
    /**
     * @param int|null $id
     *
     * @return array
     * @throws \Exception
     */
    private function save(?int $id = NULL): array {
        $request = PermissionRequest::parseOf(Request::all());
        
        if (!is_null($id)) {
            $request->id = $id;
        }
        
        $model = PermissionDTO::convertToModel($request, FALSE);
        $model = $this->getPermissionById($model->save()->id);
        $dto   = PermissionDTO::convertOfModel($model);
        
        return PermissionResponse::parseOf($dto->toArray())
                                 ->toArray();
    }
    
    private function getPermissionById($id): Model {
        if ($model = PermissionModel::find($id)) {
            return $model;
        }
        
        throw new \RuntimeException("Permiso desconocido.");
    }
    
}
