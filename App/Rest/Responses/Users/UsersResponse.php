<?php
/**
 * UserResponse.php
 */

namespace App\Rest\Responses\Users;

use App\Rest\Common\BaseRest;
use App\Rest\Dto\UsersDTO;
use App\Rest\Responses\PaginationResponse;

/**
 * @property UsersDTO[]         $users
 * @property PaginationResponse $pagination
 * Class UserResponse
 * @author Nicolás Marulanda P.
 */
class UsersResponse {
    
    use BaseRest;
    
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
    
}
