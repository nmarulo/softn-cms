<?php

namespace App\Facades;

use App\Helpers\SearchModelHelper;
use Silver\Database\Model;
use Silver\Database\Query;
use Silver\Support\Facade;

/**
 * @method static SearchModelHelper getInstance(Model $currentModel, Query $query)
 * @method static int getCount()
 * @method static Query getQuery()
 */
class SearchModelFacade extends Facade {
    
    protected static function getClass() {
        return 'App\Helpers\SearchModelHelper';
    }
    
}
