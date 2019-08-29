<?php
/**
 * Utils.php
 */

namespace App\Helpers;

use App\Rest\Common\BaseDTO;
use App\Rest\Requests\DataTable\DataTable;
use App\Rest\Requests\DataTable\Filter;
use App\Rest\Requests\DataTable\SortColumn;
use App\Rest\Common\Magic;
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
                    if (is_object($itemValue) && $this->isUseTrait($itemValue, ObjectToArray::class)) {
                        $itemValue = $this->castObjectToArray($itemValue);
                    }
                }
            } elseif (is_object($currentValue) && ($this->isUseTrait($currentValue, ObjectToArray::class) || $currentValue instanceof ObjectToArray)) {
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
        
        if ($this->isUseTrait($object, ParseOfClass::class) || $object instanceof ParseOfClass) {
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
        
        $dataTable->page           = Request::input('page');
        $dataTable->numberRowsShow = Request::input('numberRowsShow');
        
        return $dataTable;
    }
    
    public function isHiddenProperty(Model $model, string $propName): bool {
        $hiddenProps = $model->getHidden();
        
        return in_array($propName, $hiddenProps);
    }
    
    /**
     * @param array       $comparisionProps
     * @param array|Model $object
     * @param string      $classDto
     * @param bool        $hideProps
     *
     * @return mixed
     * @throws \Exception
     */
    public function castModelToDto(array $comparisionProps, $object, string $classDto, bool $hideProps = TRUE) {
        return $this->castObjectTo($comparisionProps, $object, $classDto, $hideProps);
    }
    
    /**
     * @param array         $comparisionProps
     * @param array|BaseDTO $object
     * @param string        $classModel
     * @param bool          $hideProps
     *
     * @return mixed
     * @throws \Exception
     */
    public function castDtoToModel(array $comparisionProps, $object, string $classModel, bool $hideProps = TRUE) {
        return $this->castObjectTo($comparisionProps, $object, $classModel, $hideProps);
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
    
    /**
     * @param array             $comparisionProps
     * @param array|Model|Magic $object
     * @param string            $toClass
     * @param bool              $hideProps
     *
     * @return mixed
     * @throws \Exception
     */
    private function castObjectTo(array $comparisionProps, $object, string $toClass, bool $hideProps = TRUE) {
        if (is_array($object)) {
            $result = [];
            
            foreach ($object as $value) {
                $result[] = $this->castObjectTo($comparisionProps, $value, $toClass, $hideProps);
            }
            
            return $result;
        }
        
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
        } elseif ($this->isUseTrait($object, Magic::class)) {
            $propertyNames = $object->getProperties();
        } else {
            throw new \Exception("La instancia del objeto no es valida.");
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
    
    public function isUseTrait($object, string $classTrait, bool $recursive = TRUE): bool {
        $classUses = class_uses($object);
        $result    = array_key_exists($classTrait, $classUses);
        
        if ($result || !$recursive) {
            return $result;
        }
        
        $objectClass = is_object($object) ? get_class($object) : $object;
        
        try {
            $reflection = new \ReflectionClass($objectClass);
            
            if ($parentClass = $reflection->getParentClass()) {
                $result = $this->isUseTrait($parentClass->getName(), $classTrait);
            }
            
            if (!$result) {
                foreach ($classUses as $class) {
                    if ($result = $this->isUseTrait($class, $classTrait)) {
                        break;
                    }
                }
            }
        } catch (\Exception $exception) {
            //TODO: log
            $result = FALSE;
        }
        
        return $result;
    }
}
