<?php

namespace App\Facades;

use Silver\Support\Facade;

/**
 * token event provider
 */
class Token extends Facade {
    
    protected static function getClass() {
        return 'App\Helpers\Token';
    }
    
}
