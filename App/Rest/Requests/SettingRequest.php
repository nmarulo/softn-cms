<?php
/**
 * softn-cms
 */

namespace App\Rest\Requests;

use App\Facades\UtilsFacade;
use App\Rest\Common\ParseOf;
use App\Rest\Dto\SettingDTO;

/**
 * Class SettingRequest
 * @author Nicolás Marulanda P.
 */
class SettingRequest extends SettingDTO implements ParseOf {
    
    public static function getParseOfClasses(): array {
        return [];
    }
    
    public static function parseOf(array $values): SettingRequest {
        return UtilsFacade::parseOf($values, self::class);
    }
    
}
