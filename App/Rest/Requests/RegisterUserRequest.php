<?php
/**
 * RegisterUserRequest.php
 */

namespace App\Rest\Requests;

use App\Facades\UtilsFacade;
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
        return UtilsFacade::parseOf($value, RegisterUserRequest::class);
    }
    
    public static function getParseOfClasses(): array {
        return [];
    }
    
}
