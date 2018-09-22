<?php

namespace App\Facades\Api;

use Silver\Support\Facade;
use Silver\Http\Request;

/**
 * @method static array makeResponse(\Closure $callback)
 * @method static array getRequest()
 * @method static array createResponseFormat(int $httpStatus, array $dataToSend = [])
 * @method static string getToken()
 * @method static string getTokenHeader(Request $request = NULL)
 */
class ResponseApiFacade extends Facade {
    
    protected static function getClass() {
        return 'App\Helpers\Api\ResponseApiHelper';
    }
    
}
