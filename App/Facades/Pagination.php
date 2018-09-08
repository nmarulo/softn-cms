<?php

namespace App\Facades;

use App\Helpers\Page;
use Silver\Support\Facade;

/**
 * @method static \App\Helpers\Pagination getInstance(int $currentPageValue, int $totalData, int $maxNumberPagesShow = 3)
 * @method static \App\Helpers\Pagination arrayToObject(array $values)
 * @method static int getBeginRow()
 * @method static array getPages()
 * @method static Page getLeftArrow()
 * @method static Page getRightArrow()
 * @method static bool isRendered()
 * @method static int getNumberRowShow()
 */
class Pagination extends Facade {
    
    protected static function getClass() {
        return 'App\Helpers\Pagination';
    }
    
}
