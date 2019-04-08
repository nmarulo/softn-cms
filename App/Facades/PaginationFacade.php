<?php

namespace App\Facades;

use App\Helpers\PaginationHelper;
use Silver\Support\Facade;

/**
 * @method static PaginationHelper getInit(int $totalData, ?int $currentPageValue = NULL, int $maxNumberPagesShow = 3)
 */
class PaginationFacade extends Facade {
    
    protected static function getClass() {
        return PaginationHelper::class;
    }
    
}
