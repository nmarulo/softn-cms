<?php

namespace App\Facades;

use Silver\Support\Facade;


/**
 * SearchModelFacade event provider
 */
class SearchModelFacade extends Facade
{

    protected static function getClass()
    {
        return 'App\Helpers\SearchModelHelper';
    }

}
