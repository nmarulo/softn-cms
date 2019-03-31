<?php
/**
 * UserRequest.php
 */

namespace App\Rest\Request;

use App\Facades\Utils;
use App\Rest\Common\DataTable\DataTable;
use App\Rest\Common\Magic;
use App\Rest\Common\ObjectToArray;
use App\Rest\Common\ParseOf;
use App\Rest\Dto\UsersDTO;

/**
 * @property DataTable $dataTable
 * Class UserRequest
 * @author Nicolás Marulanda P.
 */
class UserRequest extends UsersDTO implements ObjectToArray, ParseOf {
    
    use Magic;
    
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
        return Utils::parseOf($values, UserRequest::class);
    }
    
    public function toArray() {
        return Utils::castObjectToArray($this);
    }
}
