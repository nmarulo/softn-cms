<?php

namespace App\Facades;

use Silver\Support\Facade;


/**
 * messages event provider
 */
class Messages extends Facade
{

    protected static function getClass()
    {
        return 'App\Helpers\Messages';
    }

}
