<?php
/**
 * softn-cms
 */

namespace App\Controllers\Api\Dashboard\Settings;

use App\Helpers\GravatarHelper;
use App\Models\SettingsModel;
use App\Models\Users;
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
        return $this->get()
                    ->toArray();
    }
    
    /**
     * @return array
     * @throws \Exception
     */
    public function putForm() {
        $request                            = GrAvatarSettingsFormRequest::parseOf(Request::all());
        $properties                         = $request->getProperties();
        $properties['gravatarForceDefault'] = isset($properties['gravatarForceDefault']) ? $properties['gravatarForceDefault'] : FALSE;
        
        foreach ($properties as $key => $value) {
            $model                = new SettingsModel();
            $model->setting_name  = $key;
            $model->setting_value = $value;
            $model->saveByName();
        }
        
        $grAvatarSettingsFormResponse = $this->get();
        
        $this->updateUserUrlImage($grAvatarSettingsFormResponse);
        
        return $grAvatarSettingsFormResponse->toArray();
    }
    
    /**
     * @return GrAvatarSettingsFormResponse
     * @throws \Exception
     */
    private function get() {
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
        
        return $response;
    }
    
    private function updateUserUrlImage(GrAvatarSettingsFormResponse $response) {
        $users    = Users::all();
        $gravatar = new GravatarHelper($response);
        
        array_walk($users, function(Users $user) use ($gravatar) {
            $gravatar->setEmail($user->user_email);
            
            $user->user_url_image = $gravatar->get();
            $user->save();
        });
    }
}
