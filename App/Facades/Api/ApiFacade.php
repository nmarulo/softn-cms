<?php

namespace App\Facades\Api;

use Silver\Support\Facade;


/**
 * ApiFacade event provider
 */
class ApiFacade extends Facade
{

    protected static function getClass()
    {
        return 'App\Helpers\Api\ApiHelper';
    }

}
