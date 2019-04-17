<?php

namespace App\Facades;

use App\Helpers\SearchDataTableHelper;
use App\Rest\Common\DataTable\Filter;
use Silver\Database\Model;
use Silver\Database\Query;
use Silver\Support\Facade;

/**
 * @method static SearchDataTableHelper filter(Model $currentModel, Filter $filter, Query $query)
 */
class SearchDataTableFacade extends Facade {
    
    protected static function getClass() {
        return SearchDataTableHelper::class;
    }
    
}
