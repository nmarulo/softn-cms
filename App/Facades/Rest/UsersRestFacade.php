<?php
/**
 * UsersRestFacade.php
 */

namespace App\Facades\Rest;

use App\Rest\Calls\UsersRest;
use App\Rest\Requests\UserRequest;
use App\Rest\Responses\UserResponse;
use App\Rest\Responses\UsersResponse;
use Silver\Support\Facade;

/**
 * @method static UsersResponse getAll(UserRequest $request = NULL)
 * @method static UserResponse getById(int $id)
 * @method static UserResponse create(UserRequest $request)
 * @method static UserResponse update(int $id, UserRequest $request)
 * @method static bool remove(int $id)
 * Class UsersRestFacade
 * @author Nicolás Marulanda P.
 */
class UsersRestFacade extends Facade {
    
    protected static function getClass() {
        return UsersRest::class;
    }
    
}
