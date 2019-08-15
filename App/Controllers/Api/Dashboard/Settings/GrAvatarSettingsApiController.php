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
use App\Rest\Responses\Settings\Gravatar\GravatarImageResponse;
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
        $response      = new GrAvatarSettingsFormResponse();
        $settingsModel = SettingsModel::where('setting_name', 'like', 'gravatar%')
                                      ->all();
        $this->setGravatarSetting($response, $settingsModel);
        $this->setGravatarList($response, $settingsModel);
        $response->gravatarUrl = $this->getSettingFromArray($settingsModel, 'gravatarUrl');
        
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
    
    /**
     * @param array  $settings
     * @param string $name
     *
     * @return SettingsModel
     * @throws \Exception
     */
    private function getSettingFromArray(array $settings, string $name): SettingsModel {
        foreach ($settings as $setting) {
            if ($setting->setting_name == $name) {
                return $setting;
            }
        }
        
        throw new \Exception(sprintf("Configuración [%s] no encontrada.", $name));
    }
    
    private function getSettingValue(SettingsModel $setting, \Closure $closure = NULL) {
        $value = $setting->setting_value;
        
        if ($closure && is_callable($closure)) {
            return $closure($value);
        }
        
        return $value;
    }
    
    private function stringToArray(string $value, string $delimiter, \Closure $closure = NULL): array {
        $result = explode($delimiter, $value);
        
        if ($closure && is_callable($closure)) {
            return array_map($closure, $result);
        }
        
        return $result;
    }
    
    /**
     * @param GrAvatarSettingsFormResponse $response
     * @param array                        $settingsModel
     *
     * @throws \Exception
     */
    private function setGravatarList(GrAvatarSettingsFormResponse &$response, array $settingsModel): void {
        $gravatarImageList  = $this->getSettingFromArray($settingsModel, 'gravatarImageList');
        $gravatarRatingList = $this->getSettingFromArray($settingsModel, 'gravatarRatingList');
        $gravatarSizeList   = $this->getSettingFromArray($settingsModel, 'gravatarSizeList');
        $gravatarUrl        = $this->getSettingFromArray($settingsModel, 'gravatarUrl');
        
        $response->gravatarImageValueList  = $this->getSettingValue($gravatarImageList, function($value) use ($gravatarUrl) {
            return $this->stringToArray($value, ',', function($s) use ($gravatarUrl) {
                $imageResponse        = new GravatarImageResponse();
                $imageResponse->value = $s;
                $imageResponse->url   = sprintf('%1$s?d=%2$s', $gravatarUrl->setting_value, $s);
                
                return $imageResponse;
            });
        });
        $response->gravatarRatingValueList = $this->getSettingValue($gravatarRatingList, function($value) {
            return $this->stringToArray($value, ',');
        });
        $response->gravatarSizeValueList   = $this->getSettingValue($gravatarSizeList, function($value) {
            return $this->stringToArray($value, ',', function($s) {
                return intval($s);
            });
        });
    }
    
    /**
     * @param GrAvatarSettingsFormResponse $response
     * @param array                        $settingsModel
     *
     * @throws \Exception
     */
    private function setGravatarSetting(GrAvatarSettingsFormResponse &$response, array $settingsModel): void {
        $grAvatarSettingsForm = [
                'gravatarSize',
                'gravatarImage',
                'gravatarRating',
                'gravatarForceDefault',
        ];
        $gravatarSettings     = array_filter($settingsModel, function(SettingsModel $model) use ($grAvatarSettingsForm) {
            return array_search($model->setting_name, $grAvatarSettingsForm, TRUE) !== FALSE;
        });
        
        foreach ($gravatarSettings as $value) {
            $response->{$value->setting_name} = SettingDTO::convertOfModel($value);
        }
    }
}
