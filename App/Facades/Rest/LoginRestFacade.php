<?php
/**
 * LoginRestFacade.php
 */

namespace App\Facades\Rest;

use App\Rest\Calls\LoginRest;
use App\Rest\Requests\UserRequest;
use App\Rest\Responses\UserResponse;
use Silver\Support\Facade;

/**
 * @method static UserResponse login(UserRequest $request)
 * Class LoginRestFacade
 * @author Nicolás Marulanda P.
 */
class LoginRestFacade extends Facade {
    
    protected static function getClass() {
        return LoginRest::class;
    }
}
