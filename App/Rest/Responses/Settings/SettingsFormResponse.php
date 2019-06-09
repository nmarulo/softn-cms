<?php
/**
 * softn-cms
 */

namespace App\Rest\Responses\Settings;

use App\Rest\Common\BaseRest;
use App\Rest\Dto\SettingDTO;

/**
 * @property SettingDTO $title
 * @property SettingDTO $description
 * @property SettingDTO $emailAdmin
 * @property SettingDTO $siteUrl
 * Class SettingsFormResponse
 * @author Nicolás Marulanda P.
 */
class SettingsFormResponse {
    
    use BaseRest;
    
    /** @var SettingDTO */
    private $title;
    
    /** @var SettingDTO */
    private $description;
    
    /** @var SettingDTO */
    private $emailAdmin;
    
    /** @var SettingDTO */
    private $siteUrl;
    
    public static function getParseOfClasses(): array {
        return [
                'SettingDTO' => SettingDTO::class,
        ];
    }
    
}
