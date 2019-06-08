<?php
/**
 * UsersRestFacade.php
 */

namespace App\Facades\Rest;

use App\Rest\Calls\UsersRest;
use App\Rest\Requests\Users\UserRequest;
use App\Rest\Responses\Users\UserResponse;
use App\Rest\Responses\Users\UsersResponse;
use Silver\Support\Facade;

/**
 * @method static UsersResponse getAll(UserRequest $request = NULL)
 * @method static UserResponse getById(int $id)
 * @method static UserResponse create(UserRequest $request)
 * @method static UserResponse update(int $id, UserRequest $request)
 * @method static UserResponse updatePassword(int $id, UserRequest $request)
 * @method static bool remove(int $id)
 * @method static bool isError()
 * Class UsersRestFacade
 * @author Nicolás Marulanda P.
 */
class UsersRestFacade extends Facade {
    
    protected static function getClass() {
        return UsersRest::class;
    }
    
}
