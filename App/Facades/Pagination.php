<?php

namespace App\Facades;

use Silver\Support\Facade;

/**
 * @method static \App\Helpers\Pagination getInit(int $totalData, ?int $currentPageValue = NULL, int $maxNumberPagesShow = 3)
 */
class Pagination extends Facade {
    
    protected static function getClass() {
        return Pagination::class;
    }
    
}
