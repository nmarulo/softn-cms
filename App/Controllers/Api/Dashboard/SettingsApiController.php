<?php
/**
 * softn-cms
 */

namespace App\Controllers\Api\Dashboard;

use App\Facades\SearchFacade;
use App\Models\ProfileModel;
use App\Models\SettingsModel;
use App\Rest\Dto\SettingDTO;
use App\Rest\Requests\SettingRequest;
use App\Rest\Requests\Settings\SettingsFormRequest;
use App\Rest\Responses\SettingResponse;
use App\Rest\Responses\Settings\SettingsFormResponse;
use App\Rest\Responses\SettingsResponse;
use App\Rest\Responses\Users\ProfileResponse;
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
                'paginationNumberRowsShowList',
                'paginationNumberRowsDefault',
                'paginationMaxNumberPagesShow',
                'profileDefault',
        ];
        $response     = new SettingsFormResponse();
        $models       = SettingsModel::where('setting_name', 'in', $settingsForm)
                                     ->all();
        
        foreach ($models as $value) {
            $response->{$value->setting_name} = SettingDTO::convertOfModel($value);
        }
        
        $response->profiles = ProfileResponse::convertOfModel(ProfileModel::all());
        $response->paginationNumberRowsShowList->settingValue = explode(',', $response->paginationNumberRowsShowList->settingValue);
        
        return $response->toArray();
    }
    
    /**
     * @return array
     * @throws \Exception
     */
    public function putForm() {
        $request    = SettingsFormRequest::parseOf(Request::all());
        $properties = $request->getProperties();
        
        foreach ($properties as $key => $value) {
            $model                = new SettingsModel();
            $model->setting_name  = $key;
            $model->setting_value = $value;
            $model->saveByName();
        }
        
        return $this->getForm();
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
