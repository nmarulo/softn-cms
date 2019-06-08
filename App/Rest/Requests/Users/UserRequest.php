<?php
/**
 * UserRequest.php
 */

namespace App\Rest\Requests\Users;

use App\Rest\Requests\DataTable\DataTable;
use App\Rest\Requests\RegisterUserRequest;

/**
 * @property string    userCurrentPassword
 * @property DataTable $dataTable
 * Class UserRequest
 * @author Nicolás Marulanda P.
 */
class UserRequest extends RegisterUserRequest {
    
    /**
     * @var DataTable
     */
    private $dataTable;
    
    /**
     * @var string
     */
    private $userCurrentPassword;
    
    public static function getParseOfClasses(): array {
        return [
                'DataTable' => DataTable::class,
        ];
    }
    
}
