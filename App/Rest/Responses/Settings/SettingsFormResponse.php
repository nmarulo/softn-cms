<?php
/**
 * softn-cms
 */

namespace App\Rest\Responses\Settings;

use App\Rest\Common\BaseRest;
use App\Rest\Dto\SettingDTO;
use App\Rest\Responses\Users\ProfileResponse;

/**
 * @property SettingDTO $title
 * @property SettingDTO $description
 * @property SettingDTO $emailAdmin
 * @property SettingDTO $siteUrl
 * @property SettingDTO $paginationNumberRowsShowList
 * @property SettingDTO $paginationNumberRowsDefault
 * @property SettingDTO $paginationMaxNumberPagesShow
 * @property SettingDTO $profileDefault
 * @property array $profiles
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
    
    /** @var SettingDTO */
    private $paginationNumberRowsShowList;
    
    /** @var SettingDTO */
    private $paginationNumberRowsDefault;
    
    /** @var SettingDTO */
    private $paginationMaxNumberPagesShow;
    
    /** @var SettingDTO */
    private $profileDefault;
    
    /** @var ProfileResponse[] */
    private $profiles;
    
    public static function getParseOfClasses(): array {
        return [
                'SettingDTO'       => SettingDTO::class,
                'ProfileResponse' => ProfileResponse::class,
        ];
    }
    
}
