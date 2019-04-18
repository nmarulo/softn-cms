<?php
/**
 * softn-cms
 */

namespace App\Rest\Responses;

use App\Facades\UtilsFacade;
use App\Rest\Common\Magic;
use App\Rest\Common\ObjectToArray;
use App\Rest\Common\ParseOf;
use App\Rest\Dto\SettingDTO;

/**
 * @property SettingDTO[] $settings
 * Class SettingsResponse
 * @author Nicolás Marulanda P.
 */
class SettingsResponse implements ParseOf, ObjectToArray {
    
    use Magic;
    
    /**
     * @var SettingDTO[]
     */
    private $settings;
    
    public static function parseOf(array $value): SettingsResponse {
        return UtilsFacade::parseOf($value, self::class);
    }
    
    public static function getParseOfClasses(): array {
        return [
                'SettingDTO' => SettingDTO::class,
        ];
    }
    
    public function toArray(): array {
        return UtilsFacade::castObjectToArray($this);
    }
    
}
