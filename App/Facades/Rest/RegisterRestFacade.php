<?php
/**
 * RegisterRestFacade.php
 */

namespace App\Facades\Rest;

use App\Rest\RegisterRest;
use App\Rest\Request\RegisterUserRequest;
use App\Rest\Response\UserResponse;
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
