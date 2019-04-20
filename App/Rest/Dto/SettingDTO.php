<?php
/**
 * softn-cms
 */

namespace App\Rest\Dto;

use App\Models\SettingsModel;

/**
 * @property string $id
 * @property string $settingName
 * @property string $settingValue
 * @property string $settingDescription
 * Class SettingDTO
 * @author Nicolás Marulanda P.
 */
class SettingDTO extends BaseDTO {
    
    /**
     * @var int
     */
    private $id;
    
    /**
     * @var string
     */
    private $settingName;
    
    /**
     * @var string
     */
    private $settingValue;
    
    /**
     * @var string
     */
    private $settingDescription;
    
    protected static function getComparisionNameDtoToModel(): array {
        return [
                'id'                 => 'id',
                'settingName'        => 'setting_name',
                'settingValue'       => 'setting_value',
                'settingDescription' => 'setting_description',
        ];
    }
    
    protected static function getClassModel(): string {
        return SettingsModel::class;
    }
    
    protected static function getClassDTO(): string {
        return self::class;
    }
    
}
