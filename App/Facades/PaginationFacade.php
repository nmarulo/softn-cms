<?php

namespace App\Facades;

use App\Helpers\PaginationHelper;
use App\Rest\Requests\DataTable\DataTable;
use Silver\Support\Facade;

/**
 * @method static PaginationHelper getInit(int $totalData, DataTable $dataTable)
 */
class PaginationFacade extends Facade {
    
    protected static function getClass() {
        return PaginationHelper::class;
    }
    
}
