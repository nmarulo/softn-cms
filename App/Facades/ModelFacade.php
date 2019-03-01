<?php

namespace App\Facades;

use App\Helpers\ModelHelper;
use Silver\Database\Model;
use Silver\Support\Facade;

/**
 * @method static Model arrayToObject(array $array, string $class)
 * @method static ModelHelper model(string $model)
 * @method static ModelHelper search()
 * @method static ModelHelper pagination(\Closure $dataModelClosure = NULL)
 * @method static ModelHelper sort()
 * @method static array all()
 * @method static Pagination getPagination()
 */
class ModelFacade extends Facade {
    
    protected static function getClass() {
        return 'App\Helpers\ModelHelper';
    }
    
}
