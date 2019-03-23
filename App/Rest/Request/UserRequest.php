<?php
/**
 * UserRequest.php
 */

namespace App\Rest\Request;

use App\Facades\Utils;
use App\Rest\Common\DataTable\DataTable;
use App\Rest\Common\Magic;
use App\Rest\Common\ObjectToArray;
use App\Rest\Common\ParseOfClass;
use App\Rest\Dto\Users;

/**
 * @property DataTable $dataTable
 * Class UserRequest
 * @author Nicolás Marulanda P.
 */
class UserRequest extends Users implements ObjectToArray, ParseOfClass {
    
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
    
    public function toArray() {
        return Utils::castObjectToArray($this);
    }
}
