<?php
/**
 * RegisterRestFacade.php
 */

namespace App\Facades\Rest;

use App\Rest\Calls\RegisterRest;
use App\Rest\Requests\RegisterUserRequest;
use App\Rest\Responses\Users\UserResponse;
use Silver\Support\Facade;

/**
 * @method static UserResponse register(RegisterUserRequest $request)
 * Class RegisterRestFacade
 * @author Nicolás Marulanda P.
 */
class RegisterRestFacade extends Facade {
    
    protected static function getClass() {
        return RegisterRest::class;
    }
}
