<?php
/**
 * softn-cms
 */

namespace App\Facades\Rest\Settings;

use App\Rest\Calls\Settings\GrAvatarSettingsFormRest;
use App\Rest\Requests\Settings\GrAvatarSettingsFormRequest;
use App\Rest\Responses\Settings\GrAvatarSettingsFormResponse;
use Silver\Support\Facade;

/**
 * @method static GrAvatarSettingsFormResponse getForm()
 * @method static GrAvatarSettingsFormResponse putForm(GrAvatarSettingsFormRequest $request)
 * @method static bool isError()
 * Class GrAvatarSettingsFormRestFacade
 * @author Nicolás Marulanda P.
 */
class GrAvatarSettingsFormRestFacade extends Facade {
    
    protected static function getClass() {
        return GrAvatarSettingsFormRest::class;
    }
    
}
