<?php
/**
 * softn-cms
 */

namespace App\Rest\Responses\Settings;

use App\Rest\Common\BaseRest;
use App\Rest\Dto\SettingDTO;

/**
 * @property SettingDTO $gravatarSize
 * @property SettingDTO $gravatarImage
 * @property SettingDTO $gravatarRating
 * @property SettingDTO $gravatarForceDefault
 * Class GrAvatarSettingsFormResponse
 * @author Nicolás Marulanda P.
 */
class GrAvatarSettingsFormResponse {
    
    use BaseRest;
    
    /** @var SettingDTO */
    private $gravatarSize;
    
    /** @var SettingDTO */
    private $gravatarImage;
    
    /** @var SettingDTO */
    private $gravatarRating;
    
    /** @var SettingDTO */
    private $gravatarForceDefault;
    
    public static function getParseOfClasses(): array {
        return [
                'SettingDTO' => SettingDTO::class,
        ];
    }
    
}
