<?php
/**
 * Utils.php
 */

namespace App\Helpers;

use App\Rest\Common\DataTable\DataTable;
use App\Rest\Common\DataTable\Filter;
use App\Rest\Common\DataTable\SortColumn;
use App\Rest\Common\ObjectToArray;
use App\Rest\Common\ParseOfClass;
use Silver\Core\Bootstrap\Facades\Request;
use Silver\Database\Model;

/**
 * Class Utils
 * @author Nicolás Marulanda P.
 */
class Utils {
    
    public function propertiesToArray(string $class): array {
        try {
            $reflection      = new \ReflectionClass($class);
            $properties      = $reflection->getProperties();
            $propertiesArray = array_map(function(\ReflectionProperty $reflectionProperty) {
                return $reflectionProperty->getName();
            }, $properties);
            
            if ($reflection->getParentClass()) {
                $parentProperties = $this->propertiesToArray($reflection->getParentClass()
                                                                        ->getName());
                $propertiesArray  = array_merge($propertiesArray, $parentProperties);
            }
            
            return $propertiesArray;
        } catch (\ReflectionException $e) {
            return [];
        }
    }
    
    public function castObjectToArray($instance): array {
        $result          = [];
        $propertiesArray = $this->propertiesToArray(get_class($instance));
        $propertiesArray = array_filter($propertiesArray, function(string $value) use ($instance) {
            return !is_null($instance->$value);
        });
        
        foreach ($propertiesArray as $key => $value) {
            $currentValue = $instance->$value;
            
            if (is_array($currentValue)) {
                foreach ($currentValue as &$itemValue) {
                    if (is_object($itemValue) && $itemValue instanceof ObjectToArray) {
                        $itemValue = $this->castObjectToArray($itemValue);
                    }
                }
            } elseif (is_object($currentValue) && $currentValue instanceof ObjectToArray) {
                $currentValue = $currentValue->toArray();
            }
            
            if (empty($currentValue)) {
                continue;
            }
            
            $result[$value] = $currentValue;
        }
        
        return $result;
    }
    
    public function parseOf(array $array, string $class) {
        $object     = new $class();
        $arrayClass = [];
        
        if ($object instanceof ParseOfClass) {
            $arrayClass = $object::getParseOfClasses();
        }
        
        $properties = Utils::propertiesToArray($class);
        
        foreach ($array as $key => $value) {
            if (!is_array($value)) {
                if ($object instanceof Model || array_search($key, $properties, TRUE) !== FALSE) {
                    $object->$key = $value;
                }
                
                continue;
            }
            
            if (is_string($key)) {
                try {
                    $reflection    = new \ReflectionProperty($class, $key);
                    $document      = $reflection->getDocComment();
                    $propertyClass = $this->getVarNameTypeDocument($document);
                    
                    if (!empty($propertyClass) && !empty($arrayClass)) {
                        foreach ($arrayClass as $className => $classPath) {
                            if (strcasecmp($className, $propertyClass) == 0) {
                                if (is_array($value) && isset($value[0])) {
                                    foreach ($value as &$item) {
                                        $item = $this->parseOf($value, $classPath);
                                    }
                                } else {
                                    $value = $this->parseOf($value, $classPath);
                                }
                            }
                        }
                    }
                    
                    $object->$key = $value;
                } catch (\Exception $exception) {
                    //TODO: log
                }
            } elseif (is_int($key)) {
                return $this->parseOf($value, $class);
            }
        }
        
        return $object;
    }
    
    public function dateNow($format = 'Y-m-d H:i:s') {
        return date($format, time());
    }
    
    public function stringToDate($time, $format, $toFormat = 'Y-m-d H:m:s') {
        return \DateTime::createFromFormat($format, $time)
                        ->format($toFormat);
    }
    
    public function getDataTable(): DataTable {
        $dataTable       = new DataTable();
        $sortColumnInput = Request::input('sortColumn', '');
        $searchValue     = Request::input('searchValue', '');
        
        if (!empty($sortColumnInput)) {
            $sortColumnArray = [];
            
            $sortColumnInput = json_decode($sortColumnInput, TRUE);
            
            foreach ($sortColumnInput as $value) {
                $sortColumn        = new SortColumn();
                $sortColumn->name  = $value['column'];
                $sortColumn->key   = $value['sort'];
                $sortColumnArray[] = $sortColumn;
            }
            
            $dataTable->sortColumn = $sortColumnArray;
        }
        
        if (!empty($searchValue)) {
            $filter            = new Filter();
            $filter->value     = $searchValue;
            $filter->strict    = Request::input('searchStrict', '') == 'on';
            $dataTable->filter = $filter;
        }
        
        $dataTable->page = Request::input('page');
        
        return $dataTable;
    }
    
    public function isHiddenProperty(Model $model, string $propName): bool {
        $hiddenProps = $model->getHidden();
        
        return in_array($propName, $hiddenProps);
    }
    
    public function castModelToDto(array $comparisionProps, Model $model, string $classDto, bool $hideProps = TRUE) {
        return $this->castObjectTo($comparisionProps, $model, $classDto, $hideProps);
    }
    
    public function castDtoToModel(array $comparisionProps, $dto, string $classModel, bool $hideProps = TRUE) {
        return $this->castObjectTo($comparisionProps, $dto, $classModel, $hideProps);
    }
    
    private function getVarNameTypeDocument(string $document): string {
        $start         = 5;
        $len           = $start;
        $propertyClass = '';
        
        if (preg_match('/@var\s[A-Z]{1}[A-Za-z]+\[\]/', $document, $varMatch) == 1) {
            $len += 2;
        } else {
            preg_match('/@var\s[A-Z]{1}[A-Za-z]+/', $document, $varMatch);
        }
        
        if (empty($varMatch)) {
            return $propertyClass;
        }
        
        $propertyClass = $varMatch[0];
        $len           = strlen($propertyClass) - $len;
        
        return substr($propertyClass, $start, $len);
    }
    
    private function castObjectTo(array $comparisionProps, $object, string $toClass, bool $hideProps = TRUE) {
        $class     = new $toClass();
        $model     = $class;
        $needle    = 'propDto';
        $keyClass  = 'propModel';
        $keyObject = $needle;
        
        if ($object instanceof Model) {
            $propertyNames = $object->data();
            $model         = $object;
            $needle        = 'propModel';
            $keyObject     = $needle;
            $keyClass      = 'propDto';
        } else {
            $propertyNames = $object->getProperties();
        }
        
        $propertyNamesModel = array_keys($propertyNames);
        
        foreach ($comparisionProps as $propDto => $propModel) {
            if (array_search($$needle, $propertyNamesModel, TRUE) !== FALSE) {
                if ($hideProps && Utils::isHiddenProperty($model, $propModel)) {
                    continue;
                }
                
                $class->$$keyClass = $object->$$keyObject;
            }
        }
        
        return $class;
    }
}
