<?php
/**
 * softn-cms
 */

namespace App\Rest\Responses;

use App\Facades\UtilsFacade;
use App\Rest\Common\BaseRest;
use App\Rest\Dto\SettingDTO;

/**
 * @property SettingDTO[] $settings
 * Class SettingsResponse
 * @author Nicolás Marulanda P.
 */
class SettingsResponse {
    
    use BaseRest;
    
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
    
}
