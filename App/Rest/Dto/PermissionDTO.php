<?php
/**
 * softn-cms
 */

namespace App\Rest\Dto;

use App\Models\PermissionModel;
use App\Rest\Common\BaseDTO;

/**
 * @property int    $id
 * @property string $permissionName
 * @property string $permissionDescription
 * @property string $permissionKeyName
 * Class PermissionDTO
 * @author Nicolás Marulanda P.
 */
class PermissionDTO extends BaseDTO {
    
    /**
     * @var int
     */
    private $id;
    
    /**
     * @var string
     */
    private $permissionName;
    
    /**
     * @var string
     */
    private $permissionDescription;
    
    /**
     * @var string
     */
    private $permissionKeyName;
    
    protected static function getComparisionNameDtoToModel(): array {
        
        return [
                'id'                 => 'id',
                'permissionName'        => 'permission_name',
                'permissionDescription' => 'permission_description',
                'permissionKeyName'     => 'permission_key_name',
        ];
    }
    
    protected static function getClassModel(): string {
        return PermissionModel::class;
    }
    
    protected static function getClassDTO(): string {
        return self::class;
    }
    
}
