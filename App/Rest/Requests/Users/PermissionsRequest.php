<?php
/**
 * softn-cms
 */

namespace App\Rest\Requests\Users;

use App\Rest\Common\BaseRest;
use App\Rest\Dto\PermissionDTO;
use App\Rest\Requests\DataTable\DataTable;

/**
 * @property array $permissions
 * @property DataTable $dataTable
 * Class PermissionsRequest
 * @author Nicolás Marulanda P.
 */
class PermissionsRequest {
    
    use BaseRest;
    
    /**
     * @var PermissionDTO[]
     */
    private $permissions;
    
    /**
     * @var DataTable
     */
    private $dataTable;
    
    public static function getParseOfClasses(): array {
        return [
                'PermissionDTO' => PermissionDTO::class,
                'DataTable'  => DataTable::class,
        ];
    }
    
}
