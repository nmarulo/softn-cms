<?php

namespace App\Facades\Api;

use Silver\Support\Facade;


/**
 * ResponseApiFacade event provider
 */
class ResponseApiFacade extends Facade
{

    protected static function getClass()
    {
        return 'App\Helpers\Api\ResponseApiHelper';
    }

}
