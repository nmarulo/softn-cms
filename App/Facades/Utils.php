<?php

namespace App\Facades;

use App\Rest\Common\DataTable\DataTable;
use Silver\Support\Facade;

/**
 * @method static array propertiesToArray(string $class)
 * @method static array castObjectToArray($instance)
 * @method static mixed parseOf(array $array, string $class)
 * @method static false|string dateNow($format = 'Y-m-d H:i:s')
 * @method static string stringToDate($time, $format, $toFormat = 'Y-m-d H:m:s')
 * @method static DataTable getDataTable()
 */
class Utils extends Facade {
    
    protected static function getClass() {
        return 'App\Helpers\Utils';
    }
    
}
