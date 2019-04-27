<?php
/**
 * softn-cms
 */

namespace App\Rest\Responses\Users;

use App\Rest\Common\BaseRest;
use App\Rest\Dto\PermissionDTO;

/**
 * @property array $permissions
 * Class PermissionsResponse
 * @author Nicolás Marulanda P.
 */
class PermissionsResponse {
    
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
