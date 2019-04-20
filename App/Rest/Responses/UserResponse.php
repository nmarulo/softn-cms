<?php
/**
 * UserResponse.php
 */

namespace App\Rest\Responses;

use App\Facades\UtilsFacade;
use App\Rest\Common\BaseRest;
use App\Rest\Dto\UsersDTO;

/**
 * Class UserResponse
 * @author Nicolás Marulanda P.
 */
class UserResponse extends UsersDTO {
    
    use BaseRest;
    
    public static function parseOf(array $value): UserResponse {
        return UtilsFacade::parseOf($value, UserResponse::class);
    }
    
}
