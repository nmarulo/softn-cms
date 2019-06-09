<?php
/**
 * softn-cms
 */

namespace App\Facades\Rest;

use App\Rest\Calls\SettingsFormRest;
use App\Rest\Responses\Settings\SettingsFormResponse;
use Silver\Support\Facade;

/**
 * @method static SettingsFormResponse getForm()
 * Class SettingsRestFacade
 * @author Nicolás Marulanda P.
 */
class SettingsFormRestFacade extends Facade {
    
    protected static function getClass() {
        return SettingsFormRest::class;
    }
    
}
