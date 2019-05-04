<?php
/**
 * softn-cms
 */

namespace App\Controllers\Api\Dashboard\Users;

use App\Facades\SearchFacade;
use App\Models\ProfileModel;
use App\Rest\Dto\ProfileDTO;
use App\Rest\Requests\Users\ProfileRequest;
use App\Rest\Requests\Users\ProfilesRequest;
use App\Rest\Responses\Users\ProfileResponse;
use App\Rest\Responses\Users\ProfilesResponse;
use Silver\Core\Bootstrap\Facades\Request;
use Silver\Core\Controller;
use Silver\Database\Model;

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
            $dto = ProfileDTO::convertOfModel($this->getProfileById($id));
            
            return ProfileResponse::parseOf($dto->toArray())
                                  ->toArray();
        }
        
        $response = new ProfilesResponse();
        $request  = ProfilesRequest::parseOf(Request::all());
        $models   = SearchFacade::init(ProfileModel::class)
                                ->search($request->profiles)
                                ->all();
        
        $response->profiles = ProfileDTO::convertOfModel($models);
        
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
        $request = ProfileRequest::parseOf(Request::all());
        
        if (!is_null($id)) {
            $request->id = $id;
        }
        
        $model = ProfileDTO::convertToModel($request, FALSE);
        $model = $this->getProfileById($model->save()->id);
        $dto   = ProfileDTO::convertOfModel($model);
        
        return ProfileResponse::parseOf($dto->toArray())
                              ->toArray();
    }
    
    private function getProfileById($id): Model {
        if ($model = ProfileModel::find($id)) {
            return $model;
        }
        
        throw new \RuntimeException("Perfil desconocido.");
    }
    
}
