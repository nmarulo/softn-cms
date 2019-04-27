<?php
/**
 * softn-cms
 */

namespace App\Rest\Dto;

use App\Models\ProfileModel;
use App\Rest\Common\BaseDTO;

/**
 * @property int    $id
 * @property string $profileName
 * @property string $profileDescription
 * @property string $profileKeyName
 * Class ProfileDTO
 * @author Nicolás Marulanda P.
 */
class ProfileDTO extends BaseDTO {
    
    /**
     * @var int
     */
    private $id;
    
    /**
     * @var string
     */
    private $profileName;
    
    /**
     * @var string
     */
    private $profileDescription;
    
    /**
     * @var string
     */
    private $profileKeyName;
    
    protected static function getComparisionNameDtoToModel(): array {
        
        return [
                'id'                 => 'id',
                'profileName'        => 'profile_name',
                'profileDescription' => 'profile_description',
                'profileKeyName'     => 'profile_key_name',
        ];
    }
    
    protected static function getClassModel(): string {
        return ProfileModel::class;
    }
    
    protected static function getClassDTO(): string {
        return self::class;
    }
    
}
