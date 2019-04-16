<?php
/**
 * UsersRestFacade.php
 */

namespace App\Facades\Rest;

use App\Rest\Request\UserRequest;
use App\Rest\Response\UsersResponse;
use App\Rest\Calls\UsersRest;
use Silver\Support\Facade;

/**
 * @method static UsersResponse getAll(UserRequest $request = NULL)
 * @method static UsersResponse getById(int $id)
 * @method static UsersResponse create(UserRequest $request)
 * @method static UsersResponse update(int $id, UserRequest $request)
 * @method static bool remove(int $id)
 * Class UsersRestFacade
 * @author Nicolás Marulanda P.
 */
class UsersRestFacade extends Facade {
    
    protected static function getClass() {
        return UsersRest::class;
    }
    
}
