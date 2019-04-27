<?php
/**
 * softn-cms
 */

namespace App\Rest\Requests\Users;

use App\Rest\Common\BaseRest;
use App\Rest\Dto\PermissionDTO;

/**
 * @property array $permissions
 * Class PermissionsRequest
 * @author Nicolás Marulanda P.
 */
class PermissionsRequest {
    
    use BaseRest;
    
    /**
     * @var PermissionDTO[]
     */
    private $permissions;
    
    public static function getParseOfClasses(): array {
        return [
                'PermissionDTO' => PermissionDTO::class,
        ];
    }
    
}
