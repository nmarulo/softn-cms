<?php

namespace App\Facades;

use Silver\Support\Facade;

/**
 * ModelFacade event provider
 */
class ModelFacade extends Facade {
    
    protected static function getClass() {
        return 'App\Helpers\ModelHelper';
    }
    
}
