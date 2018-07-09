<?php

namespace App\Facades;

use Silver\Support\Facade;

/**
 * ViewFacade event provider
 */
class ViewFacade extends Facade {
    
    protected static function getClass() {
        return 'App\Helpers\ViewHelper';
    }
    
}
