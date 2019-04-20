<?php
/**
 * softn-cms
 */

namespace App\Rest\Responses;

use App\Facades\UtilsFacade;
use App\Rest\Common\BaseRest;
use App\Rest\Dto\SettingDTO;

/**
 * Class SettingResponse
 * @author Nicolás Marulanda P.
 */
class SettingResponse extends SettingDTO {
    
    use BaseRest;
    
    public static function parseOf(array $value): SettingResponse {
        return UtilsFacade::parseOf($value, self::class);
    }
}
