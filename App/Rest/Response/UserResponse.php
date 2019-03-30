<?php
/**
 * UserResponse.php
 */

namespace App\Rest\Response;

use App\Facades\Utils;
use App\Rest\Common\Magic;
use App\Rest\Common\ObjectToArray;
use App\Rest\Common\ParseOfClass;
use App\Rest\Dto\UsersDTO;

/**
 * @property UsersDTO[]         $users
 * @property PaginationResponse $pagination
 * Class UserResponse
 * @author Nicolás Marulanda P.
 */
class UserResponse implements ParseOfClass, ObjectToArray {
    
    use Magic;
    
    /**
     * @var UsersDTO[]
     */
    private $users;
    
    /**
     * @var PaginationResponse
     */
    private $pagination;
    
    public static function getParseOfClasses(): array {
        return [
                'UsersDTO'           => UsersDTO::class,
                'PaginationResponse' => PaginationResponse::class,
        ];
    }
    
    public function toArray() {
        return Utils::castObjectToArray($this);
    }
    
}
