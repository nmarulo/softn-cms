<?php
/**
 * softn-cms
 */

namespace App\Controllers\Api\Dashboard;

use App\Models\SettingsModel;
use App\Rest\Dto\SettingDTO;
use App\Rest\Requests\SettingRequest;
use App\Rest\Responses\SettingResponse;
use App\Rest\Responses\SettingsResponse;
use Silver\Core\Bootstrap\Facades\Request;
use Silver\Core\Controller;
use Silver\Database\Model;

/**
 * Class SettingsApiController
 * @author Nicolás Marulanda P.
 */
class SettingsApiController extends Controller {
    
    /**
     * @param $id
     *
     * @return SettingResponse|array
     * @throws \Exception
     */
    public function get($id) {
        $settingModel = NULL;
        
        if ($id) {
            $settingDTO = SettingDTO::convertOfModel($this->getSettingById($id));
            
            return SettingResponse::parseOf($settingDTO->toArray())
                                  ->toArray();
        } else {
            $request = SettingRequest::parseOf(Request::all());
            
            if ($request->getProperties()) {
                $settingModel = SettingDTO::convertToModel($request);
            }
            
            $settingModel = $this->search($settingModel);
        }
        
        $response           = new SettingsResponse();
        $response->settings = SettingDTO::convertOfModel($settingModel);
        
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
    
    private function search(Model $model = NULL): array {
        $query = SettingsModel::query();
        
        if ($model) {
            $properties = $model->data();
            
            foreach ($properties as $key => $value) {
                $query->where($key, '=', $value, 'and');
            }
        }
        
        return $query->all();
    }
    
    private function getSettingById($id) {
        if ($setting = SettingsModel::find($id)) {
            return $setting;
        }
        
        throw new \RuntimeException("Configuración desconocida.");
    }
    
}
