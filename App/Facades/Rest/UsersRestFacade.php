<?php
/**
 * UsersRestFacade.php
 */

namespace App\Facades\Rest;

use App\Rest\Request\UserRequest;
use App\Rest\Response\UserResponse;
use App\Rest\UsersRest;
use Silver\Support\Facade;

/**
 * @method static UserResponse getAll(UserRequest $users = NULL)
 * @method static UserResponse getById(int $id)
 * Class UsersRestFacade
 * @author Nicolás Marulanda P.
 */
class UsersRestFacade extends Facade {
    
    protected static function getClass() {
        return UsersRest::class;
    }
    
}
