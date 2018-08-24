<?php

namespace App\Facades;

use Silver\Support\Facade;

/**
 * token event provider
 */
class TokenFacade extends Facade {
    
    protected static function getClass() {
        return 'App\Helpers\TokenHelper';
    }
    
}
