<?php

namespace App\Facades;

use Silver\Support\Facade;

/**
 * utils event provider
 */
class Utils extends Facade {
    
    protected static function getClass() {
        return 'App\Helpers\Utils';
    }
    
}
