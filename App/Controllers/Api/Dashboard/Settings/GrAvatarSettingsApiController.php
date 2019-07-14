<?php
/**
 * softn-cms
 */

namespace App\Controllers\Api\Dashboard\Settings;

use App\Models\SettingsModel;
use App\Rest\Dto\SettingDTO;
use App\Rest\Requests\Settings\GrAvatarSettingsFormRequest;
use App\Rest\Responses\Settings\GrAvatarSettingsFormResponse;
use Silver\Core\Bootstrap\Facades\Request;
use Silver\Core\Controller;

/**
 * Class GrAvatarSettingsApiController
 * @author Nicolás Marulanda P.
 */
class GrAvatarSettingsApiController extends Controller {
    
    /**
     * @return array
     * @throws \Exception
     */
    public function getForm() {
        $grAvatarSettingsForm = [
                'gravatarSize',
                'gravatarImage',
                'gravatarRating',
                'gravatarForceDefault',
        ];
        $response             = new GrAvatarSettingsFormResponse();
        $models               = SettingsModel::all();
        $models               = array_filter($models, function(SettingsModel $model) use ($grAvatarSettingsForm) {
            return array_search($model->setting_name, $grAvatarSettingsForm, TRUE) !== FALSE;
        });
        foreach ($models as $value) {
            $response->{$value->setting_name} = SettingDTO::convertOfModel($value);
        }
        
        return $response->toArray();
    }
    
    /**
     * @return array
     * @throws \Exception
     */
    public function putForm() {
        $request    = GrAvatarSettingsFormRequest::parseOf(Request::all());
        $properties = $request->getProperties();
        $properties['gravatarForceDefault'] = isset($properties['gravatarForceDefault']) ? $properties['gravatarForceDefault'] : false;
        
        foreach ($properties as $key => $value) {
            $model                = new SettingsModel();
            $model->setting_name  = $key;
            $model->setting_value = $value;
            $model->saveByName();
        }
        
        return $this->getForm();
    }
}
