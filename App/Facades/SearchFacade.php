<?php

namespace App\Facades;

use App\Helpers\SearchHelper;
use Silver\Support\Facade;

/**
 * @method static SearchHelper init(string $model)
 */
class SearchFacade extends Facade {
    
    protected static function getClass() {
        return SearchHelper::class;
    }
    
}
