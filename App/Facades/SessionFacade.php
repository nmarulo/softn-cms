<?php
/**
 * softn-cms
 */

namespace App\Facades;

use App\Helpers\SessionHelper;
use App\Rest\Responses\Users\UserResponse;
use Silver\Support\Facade;

/**
 * @method static UserResponse getUser(bool $currentData = TRUE)
 * @method static void setUser(UserResponse $response)
 * @method static bool isUserExists()
 * @method static void delete()
 * @method static string getToken()
 * @method static void setToken(string $token)
 * Class SessionFacade
 * @author Nicolás Marulanda P.
 */
class SessionFacade extends Facade {
    
    protected static function getClass() {
        return SessionHelper::class;
    }
}
