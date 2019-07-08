<?php
/**
 * softn-cms
 */

namespace App\Rest\Responses\Users;

use App\Rest\Common\BaseRest;
use App\Rest\Dto\ProfileDTO;

/**
 * @property array $permissions
 * Class ProfileResponse
 * @author Nicolás Marulanda P.
 */
class ProfileResponse extends ProfileDTO {
    
    use BaseRest;
    
    /** @var PermissionResponse[] */
    private $permissions;
    
    public static function getParseOfClasses(): array {
        return [
                'PermissionResponse' => PermissionResponse::class,
        ];
    }
    
}
