<?php
/**
 * softn-cms
 */

namespace App\Rest\Common;

use App\Facades\UtilsFacade;

/**
 * Class BaseDTO
 * @author Nicolás Marulanda P.
 */
abstract class BaseDTO implements ConvertModel {
    
    use Magic, ObjectToArray;
    
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
    
}
