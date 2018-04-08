<?php

namespace App\Facades;

use Silver\Support\Facade;


/**
 * pagination event provider
 */
class Pagination extends Facade
{

    protected static function getClass()
    {
        return 'App\Helpers\Pagination';
    }

}
