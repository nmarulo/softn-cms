<?php
/**
 * UserResponse.php
 */

namespace App\Rest\Responses\Users;

use App\Rest\Common\BaseRest;
use App\Rest\Dto\UsersDTO;

/**
 * @property ProfileResponse $profile
 * Class UserResponse
 * @author Nicolás Marulanda P.
 */
class UserResponse extends UsersDTO {
    
    use BaseRest;
    
    /** @var ProfileResponse */
    private $profile;
    
    public static function getParseOfClasses(): array {
        return [
                'ProfileResponse' => ProfileResponse::class,
        ];
    }
    
}
