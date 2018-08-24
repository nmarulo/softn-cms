<?php

namespace App\Facades\Api;

use Silver\Support\Facade;


/**
 * RequestApiFacade event provider
 */
class RequestApiFacade extends Facade
{

    protected static function getClass()
    {
        return 'App\Helpers\Api\RequestApiHelper';
    }

}
