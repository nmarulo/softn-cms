<?php
/**
 * softn-cms
 */

namespace App\Rest\Responses\Users;

use App\Rest\Common\BaseRest;

/**
 * @property array $permissions
 * Class PermissionsResponse
 * @author Nicolás Marulanda P.
 */
class PermissionsResponse {
    
    use BaseRest;
    
    /**
     * @var PermissionResponse[]
     */
    private $permissions;
    
    public static function getParseOfClasses(): array {
        return [
                'PermissionResponse' => PermissionResponse::class,
        ];
    }
    
}
