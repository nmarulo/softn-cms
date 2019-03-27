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
 * @method static UserResponse getAll(UserRequest $request = NULL)
 * @method static UserResponse getById(int $id)
 * @method static UserResponse create(UserRequest $request)
 * @method static UserResponse update(int $id, UserRequest $request)
 * Class UsersRestFacade
 * @author Nicolás Marulanda P.
 */
class UsersRestFacade extends Facade {
    
    protected static function getClass() {
        return UsersRest::class;
    }
    
}
