<?php

namespace App\Facades\Api;

use Silver\Support\Facade;

/**
 * RestCallFacade event provider
 */
class RestCallFacade extends Facade {
    
    protected static function getClass() {
        return 'App\Helpers\Api\RestCallHelper';
    }
    
}
