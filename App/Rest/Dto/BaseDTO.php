<?php
/**
 * softn-cms
 */

namespace App\Rest\Dto;

use App\Facades\UtilsFacade;
use App\Rest\Common\ConvertModel;
use App\Rest\Common\Magic;
use App\Rest\Common\ObjectToArray;

/**
 * Class BaseDTO
 * @author Nicolás Marulanda P.
 */
abstract class BaseDTO implements ObjectToArray, ConvertModel {
    
    use Magic;
    
    public static function convertToModel($object, bool $hideProps = TRUE) {
        $classModel = self::getCalledClass()::getClassModel();
        
        return UtilsFacade::castDtoToModel(self::getComparisionNames(), $object, $classModel, $hideProps);
    }
    
    public static function convertOfModel($model, bool $hideProps = TRUE) {
        $classDTO = self::getCalledClass()::getClassDTO();
        
        return UtilsFacade::castModelToDto(self::getComparisionNames(), $model, $classDTO, $hideProps);
    }
    
    protected static abstract function getComparisionNameDtoToModel(): array;
    
    protected static abstract function getClassModel(): string;
    
    protected static abstract function getClassDTO(): string;
    
    private static function getComparisionNames() {
        $class = self::getCalledClass();
        
        return $class::getComparisionNameDtoToModel();
    }
    
    private static function getCalledClass(): BaseDTO {
        $class = get_called_class();
        
        return new $class();
    }
    
    public function toArray(): array {
        return UtilsFacade::castObjectToArray($this);
    }
}
