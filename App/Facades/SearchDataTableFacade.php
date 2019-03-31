<?php

namespace App\Facades;

use App\Helpers\SearchDataTable;
use App\Rest\Common\DataTable\Filter;
use Silver\Database\Model;
use Silver\Database\Query;
use Silver\Support\Facade;

/**
 * @method static SearchDataTable filter(Model $currentModel, Filter $filter, Query $query)
 */
class SearchDataTableFacade extends Facade {
    
    protected static function getClass() {
        return 'App\Helpers\SearchDataTable';
    }
    
}
