<?php

namespace App\Facades;

use Silver\Support\Facade;


/**
 * auth event provider
 */
class Auth extends Facade
{

    protected static function getClass()
    {
        return 'App\Helpers\Auth';
    }

}
