<?php
/**
 * softn-cms
 */

namespace App\Controllers\Api\Dashboard;

use App\Facades\SearchFacade;
use App\Models\SettingsModel;
use App\Rest\Dto\SettingDTO;
use App\Rest\Requests\SettingRequest;
use App\Rest\Responses\SettingResponse;
use App\Rest\Responses\SettingsResponse;
use Silver\Core\Bootstrap\Facades\Request;
use Silver\Core\Controller;

/**
 * Class SettingsApiController
 * @author Nicolás Marulanda P.
 */
class SettingsApiController extends Controller {
    
    /**
     * @param $id
     *
     * @return array
     * @throws \Exception
     */
    public function get($id) {
        if ($id) {
            $settingDTO = SettingDTO::convertOfModel($this->getSettingById($id));
            
            return SettingResponse::parseOf($settingDTO->toArray())
                                  ->toArray();
        }
        
        $response = new SettingsResponse();
        $request  = SettingRequest::parseOf(Request::all());
        $models   = SearchFacade::init(SettingsModel::class)
                                ->search(SettingDTO::convertToModel($request))
                                ->all();
        
        $response->settings = SettingDTO::convertOfModel($models);
        
        return $response->toArray();
    }
    
    /**
     * @param $id
     *
     * @return array
     * @throws \Exception
     */
    public function put($id) {
        if (is_null($id)) {
            throw new \Exception("El id no puede ser nulo.");
        }
        
        $request     = SettingRequest::parseOf(Request::all());
        $request->id = $id;
        SettingDTO::convertToModel($request)
                  ->save();
        $settingDTO = SettingDTO::convertOfModel($this->getSettingById($id));
        
        return SettingResponse::parseOf($settingDTO->toArray())
                              ->toArray();
    }
    
    private function getSettingById($id) {
        if ($setting = SettingsModel::find($id)) {
            return $setting;
        }
        
        throw new \RuntimeException("Configuración desconocida.");
    }
    
}
