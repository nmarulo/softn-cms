<?php
/**
 * softn-cms
 */

namespace App\Rest\Requests;

use App\Facades\UtilsFacade;
use App\Rest\Common\Magic;
use App\Rest\Common\ObjectToArray;
use App\Rest\Common\ParseOf;
use App\Rest\Dto\SettingDTO;

/**
 * Class SettingRequest
 * @author Nicolás Marulanda P.
 */
class SettingRequest extends SettingDTO implements ObjectToArray, ParseOf {
    
    use Magic;
    
    public static function getParseOfClasses(): array {
        return [];
    }
    
    public static function parseOf(array $values): SettingRequest {
        return UtilsFacade::parseOf($values, self::class);
    }
    
    public function toArray(): array {
        return UtilsFacade::castObjectToArray($this);
    }
}
