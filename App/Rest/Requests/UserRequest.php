<?php
/**
 * UserRequest.php
 */

namespace App\Rest\Requests;

use App\Rest\Common\BaseRest;
use App\Rest\Dto\UsersDTO;
use App\Rest\Requests\DataTable\DataTable;

/**
 * @property DataTable $dataTable
 * Class UserRequest
 * @author Nicolás Marulanda P.
 */
class UserRequest extends UsersDTO {
    
    use BaseRest;
    
    /**
     * @var DataTable
     */
    private $dataTable;
    
    public static function getParseOfClasses(): array {
        return [
                'DataTable' => DataTable::class,
        ];
    }
    
}
