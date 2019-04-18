<?php
/**
 * softn-cms
 */

namespace App\Rest\Dto;

use App\Facades\UtilsFacade;
use App\Models\SettingsModel;
use App\Rest\Common\ConvertModel;
use App\Rest\Common\Magic;
use App\Rest\Common\ObjectToArray;

/**
 * @property string $id
 * @property string $settingName
 * @property string $settingValue
 * @property string $settingDescription
 * Class SettingDTO
 * @author Nicolás Marulanda P.
 */
class SettingDTO implements ObjectToArray, ConvertModel {
    
    use Magic;
    
    const COMPARISION_TABLE = [
            'id'                 => 'id',
            'settingName'        => 'setting_name',
            'settingValue'       => 'setting_value',
            'settingDescription' => 'setting_description',
    ];
    
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
    
    public static function convertToModel($object, bool $hideProps = TRUE) {
        return UtilsFacade::castDtoToModel(self::COMPARISION_TABLE, $object, SettingsModel::class, $hideProps);
    }
    
    public static function convertOfModel($model, bool $hideProps = TRUE) {
        return UtilsFacade::castModelToDto(self::COMPARISION_TABLE, $model, self::class, $hideProps);
    }
    
    public function toArray(): array {
        return UtilsFacade::castObjectToArray($this);
    }
    
}
