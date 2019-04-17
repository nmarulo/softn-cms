<?php

namespace App\Facades;

use App\Helpers\Utils;
use App\Rest\Common\DataTable\DataTable;
use Silver\Database\Model;
use Silver\Support\Facade;

/**
 * @method static array propertiesToArray(string $class)
 * @method static array castObjectToArray($instance)
 * @method static mixed parseOf(array $array, string $class)
 * @method static false|string dateNow($format = 'Y-m-d H:i:s')
 * @method static string stringToDate($time, $format, $toFormat = 'Y-m-d H:m:s')
 * @method static DataTable getDataTable()
 * @method static bool isHiddenProperty(Model $model, string $propName)
 * @method static mixed castModelToDto(array $comparisionProps, Model $model, string $classDto, bool $hideProps = TRUE)
 * @method static mixed castDtoToModel(array $comparisionProps, $dto, string $classModel, bool $hideProps = TRUE)
 */
class UtilsFacade extends Facade {
    
    protected static function getClass() {
        return Utils::class;
    }
    
}
