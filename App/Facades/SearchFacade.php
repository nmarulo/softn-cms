<?php

namespace App\Facades;

use App\Helpers\SearchHelper;
use App\Rest\Common\DataTable\DataTable;
use Silver\Support\Facade;

/**
 * @method static SearchHelper init(string $model)
 */
class SearchFacade extends Facade {
    
    protected static function getClass() {
        return SearchHelper::class;
    }
    
}
