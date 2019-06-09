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
use App\Rest\Responses\Settings\SettingsFormResponse;
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
            $response = $this->getById($id);
        } else {
            $response = $this->getAll();
        }
        
        return $response->toArray();
    }
    
    /**
     * @return array
     * @throws \Exception
     */
    public function getForm() {
        //TODO: settingsForm, lista temporal.
        $settingsForm = [
                'title',
                'description',
                'siteUrl',
                'emailAdmin',
        ];
        $response     = new SettingsFormResponse();
        $models       = SettingsModel::all();
        $models       = array_filter($models, function(SettingsModel $model) use ($settingsForm) {
            return array_search($model->setting_name, $settingsForm, TRUE) !== FALSE;
        });
        
        foreach ($models as $value) {
            $response->{$value->setting_name} = SettingDTO::convertOfModel($value);
        }
        
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
    
    /**
     * @return SettingsResponse
     * @throws \Exception
     */
    private function getAll(): SettingsResponse {
        $response           = new SettingsResponse();
        $request            = SettingRequest::parseOf(Request::all());
        $models             = SearchFacade::init(SettingsModel::class)
                                          ->search(SettingDTO::convertToModel($request))
                                          ->all();
        $response->settings = SettingDTO::convertOfModel($models);
        
        return $response;
    }
    
    /**
     * @param int $id
     *
     * @return SettingResponse
     * @throws \Exception
     */
    private function getById(int $id): SettingResponse {
        $settingDTO = SettingDTO::convertOfModel($this->getSettingById($id));
        
        return SettingResponse::parseOf($settingDTO->toArray());
    }
    
}
