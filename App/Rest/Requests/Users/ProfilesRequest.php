<?php
/**
 * softn-cms
 */

namespace App\Rest\Requests\Users;

use App\Rest\Common\BaseRest;
use App\Rest\Dto\ProfileDTO;
use App\Rest\Requests\DataTable\DataTable;

/**
 * @property array $profiles
 * @property DataTable $dataTable
 * Class ProfilesRequest
 * @author Nicolás Marulanda P.
 */
class ProfilesRequest {
    
    use BaseRest;
    
    /**
     * @var ProfileDTO[]
     */
    private $profiles;
    
    /**
     * @var DataTable
     */
    private $dataTable;
    
    public static function getParseOfClasses(): array {
        return [
                'ProfileDTO' => ProfileDTO::class,
                'DataTable'  => DataTable::class,
        ];
    }
    
}
