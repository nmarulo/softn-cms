<?php
/**
 * UserResponse.php
 */

namespace App\Rest\Response;

use App\Facades\Utils;
use App\Rest\Common\ParseOf;
use App\Rest\Dto\UsersDTO;

/**
 * Class UserResponse
 * @author Nicolás Marulanda P.
 */
class UserResponse extends UsersDTO implements ParseOf {
    
    public static function parseOf(array $value): UserResponse {
        return Utils::parseOf($value, UserResponse::class);
    }
    
    public static function getParseOfClasses(): array {
        return [];
    }
    
}
