<?php
/**
 * UserRequest.php
 */

namespace App\Rest\Requests;

use App\Facades\UtilsFacade;
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
    
    public static function parseOf(array $values): UserRequest {
        return UtilsFacade::parseOf($values, UserRequest::class);
    }
    
}
