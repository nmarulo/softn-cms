<?php
/**
 * softn-cms
 */

namespace App\Rest\Requests;

use App\Facades\UtilsFacade;
use App\Rest\Common\BaseRest;
use App\Rest\Dto\SettingDTO;

/**
 * Class SettingRequest
 * @author Nicolás Marulanda P.
 */
class SettingRequest extends SettingDTO {
    
    use BaseRest;
    
    public static function parseOf(array $values): SettingRequest {
        return UtilsFacade::parseOf($values, self::class);
    }
    
}
