<?php
/**
 * UserRequest.php
 */

namespace App\Rest\Requests;

use App\Facades\UtilsFacade;
use App\Rest\Requests\DataTable\DataTable;
use App\Rest\Common\ParseOf;
use App\Rest\Dto\UsersDTO;

/**
 * @property DataTable $dataTable
 * Class UserRequest
 * @author Nicolás Marulanda P.
 */
class UserRequest extends UsersDTO implements ParseOf {
    
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
