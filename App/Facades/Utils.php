<?php

namespace App\Facades;

use Silver\Support\Facade;

/**
 * @method static false|string dateNow($format = 'Y-m-d H:i:s')
 * @method static string stringToDate($time, $format, $toFormat = 'Y-m-d H:m:s')
 */
class Utils extends Facade {
    
    protected static function getClass() {
        return 'App\Helpers\Utils';
    }
    
}
