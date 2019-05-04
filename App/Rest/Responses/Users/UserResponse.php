<?php
/**
 * UserResponse.php
 */

namespace App\Rest\Responses\Users;

use App\Rest\Common\BaseRest;
use App\Rest\Dto\UsersDTO;

/**
 * Class UserResponse
 * @author Nicolás Marulanda P.
 */
class UserResponse extends UsersDTO {
    
    use BaseRest;
    
}
