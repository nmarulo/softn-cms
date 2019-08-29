<?php
/**
 * softn-cms
 */

namespace App\Rest\Responses\Users;

use App\Rest\Common\BaseRest;
use App\Rest\Responses\PaginationResponse;

/**
 * @property array $permissions
 * @property PaginationResponse $pagination
 * Class PermissionsResponse
 * @author Nicolás Marulanda P.
 */
class PermissionsResponse {
    
    use BaseRest;
    
    /**
     * @var PermissionResponse[]
     */
    private $permissions;
    
    /**
     * @var PaginationResponse
     */
    private $pagination;
    
    public static function getParseOfClasses(): array {
        return [
                'PermissionResponse' => PermissionResponse::class,
                'PaginationResponse' => PaginationResponse::class,
        ];
    }
    
}
