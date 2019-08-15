<?php
/**
 * softn-cms
 */

namespace App\Rest\Responses\Settings;

use App\Rest\Common\BaseRest;
use App\Rest\Dto\SettingDTO;
use App\Rest\Responses\Settings\Gravatar\GravatarImageResponse;

/**
 * @property SettingDTO $gravatarSize
 * @property array      $gravatarSizeValueList
 * @property SettingDTO $gravatarImage
 * @property array      $gravatarImageValueList
 * @property SettingDTO $gravatarRating
 * @property array      $gravatarRatingValueList
 * @property SettingDTO $gravatarForceDefault
 * @property SettingDTO $gravatarUrl
 * Class GrAvatarSettingsFormResponse
 * @author Nicolás Marulanda P.
 */
class GrAvatarSettingsFormResponse {
    
    use BaseRest;
    
    /** @var SettingDTO */
    private $gravatarSize;
    
    /** @var array */
    private $gravatarSizeValueList;
    
    /** @var SettingDTO */
    private $gravatarImage;
    
    /** @var GravatarImageResponse[] */
    private $gravatarImageValueList;
    
    /** @var SettingDTO */
    private $gravatarRating;
    
    /** @var array */
    private $gravatarRatingValueList;
    
    /** @var SettingDTO */
    private $gravatarForceDefault;
    
    /** @var SettingDTO */
    private $gravatarUrl;
    
    public static function getParseOfClasses(): array {
        return [
                'SettingDTO'            => SettingDTO::class,
                'GravatarImageResponse' => GravatarImageResponse::class,
        ];
    }
    
}
