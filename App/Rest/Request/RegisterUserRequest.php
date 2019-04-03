<?php
/**
 * RegisterUserRequest.php
 */

namespace App\Rest\Request;

use App\Facades\Utils;
use App\Rest\Common\ParseOf;
use App\Rest\Dto\UsersDTO;

/**
 * @property string $userPasswordRe
 * Class RegisterUserRequest
 * @author Nicolás Marulanda P.
 */
class RegisterUserRequest extends UsersDTO implements ParseOf {
    
    /**
     * @var string
     */
    private $userPasswordRe;
    
    public static function parseOf(array $value): RegisterUserRequest {
        return Utils::parseOf($value, RegisterUserRequest::class);
    }
    
    public static function getParseOfClasses(): array {
        return [];
    }
    
}
