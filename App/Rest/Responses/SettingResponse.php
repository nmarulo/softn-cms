<?php
/**
 * softn-cms
 */

namespace App\Rest\Responses;

use App\Facades\UtilsFacade;
use App\Rest\Common\ParseOf;
use App\Rest\Dto\SettingDTO;

/**
 * Class SettingResponse
 * @author Nicolás Marulanda P.
 */
class SettingResponse extends SettingDTO implements ParseOf {
    
    public static function parseOf(array $value): SettingResponse {
        return UtilsFacade::parseOf($value, self::class);
    }
    
    public static function getParseOfClasses(): array {
        return [];
    }
}
