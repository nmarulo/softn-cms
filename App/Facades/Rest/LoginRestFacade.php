<?php
/**
 * LoginRestFacade.php
 */

namespace App\Facades\Rest;

use App\Rest\LoginRest;
use App\Rest\Request\UserRequest;
use App\Rest\Response\UserResponse;
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
