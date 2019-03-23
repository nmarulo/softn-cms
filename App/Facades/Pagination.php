<?php

namespace App\Facades;

use Silver\Support\Facade;

/**
 * @method static \App\Helpers\Pagination getInit(int $currentPageValue, int $totalData, int $maxNumberPagesShow = 3)
 */
class Pagination extends Facade {
    
    protected static function getClass() {
        return 'App\Helpers\Pagination';
    }
    
}
