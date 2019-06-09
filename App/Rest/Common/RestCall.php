<?php
/**
 * RestCall.php
 */

namespace App\Rest\Common;

use App\Facades\UtilsFacade;
use App\Helpers\Api\RequestApiHelper;

/**
 * Class RestCall
 * @author Nicolás Marulanda P.
 */
abstract class RestCall {
    
    /**
     * @var RequestApiHelper
     */
    private $requestApiHelper;
    
    /**
     * @var \Closure
     */
    private $methodGetClassParseTo;
    
    /**
     * RestCall constructor.
     */
    protected function __construct() {
        $this->requestApiHelper      = new RequestApiHelper();
        $this->methodGetClassParseTo = NULL;
    }
    
    public function isError() {
        return $this->requestApiHelper->isError();
    }
    
    /**
     * @param mixed       $object
     * @param string      $uri
     * @param string|null $parseTo
     *
     * @return mixed
     * @throws \Exception
     */
    protected function get($object = NULL, string $uri = '', string $parseTo = NULL) {
        $uri                         = $this->buildUri($object, $uri);
        $this->methodGetClassParseTo = $parseTo;
        
        return $this->makeCall('get', $object, $uri);
    }
    
    /**
     * @param        $object
     *
     * @return mixed
     * @throws \Exception
     */
    protected function post($object) {
        return $this->makeCall('post', $object);
    }
    
    /**
     * @param int    $id
     * @param        $object
     * @param string $uri
     *
     * @return mixed
     * @throws \Exception
     */
    protected function put(int $id, $object, string $uri = '') {
        return $this->makeCall('put', $object, $this->buildUri($id, $uri));
    }
    
    /**
     * @param int $id
     *
     * @return mixed
     * @throws \Exception
     */
    protected function delete(int $id) {
        return $this->makeCall('delete', NULL, $id);
    }
    
    protected abstract function baseClassParseTo(): string;
    
    protected abstract function baseUri(): string;
    
    protected abstract function catchException(\Exception $exception): void;
    
    /**
     * @param string $type
     * @param mixed  $object
     * @param string $uri
     *
     * @return mixed
     * @throws \Exception
     */
    private function makeCall(string $type, $object = NULL, string $uri = '') {
        $uri = trim($this->baseUri(), '/') . "/$uri";
        
        if (is_object($object) && UtilsFacade::isUseTrait($object, ObjectToArray::class)) {
            $object = $object->toArray();
        }
        
        try {
            $this->requestApiHelper->$type($uri, $object);
            
            if ($this->requestApiHelper->isError()) {
                throw new \Exception($this->requestApiHelper->getMessage());
            }
            
            $response     = $this->requestApiHelper->responseJsonDecode();
            $classParseOf = $this->baseClassParseTo();
            
            if ($type == 'get' && $this->methodGetClassParseTo) {
                $classParseOf = $this->methodGetClassParseTo;
            }
            
            if (!UtilsFacade::isUseTrait($classParseOf, ParseOf::class)) {
                throw new \Exception("No se puede convertir la respuesta al tipo de clase especificado.");
            }
            
            return $classParseOf::parseOf($response);
        } catch (\Exception $exception) {
            $this->catchException($exception);
            throw $exception;
        }
    }
    
    private function buildUri(&$object, string $uri = ''): string {
        $uri = trim($uri, '/');
        
        if (is_int($object)) {
            $uri = "$uri/$object";
        } elseif (is_array($object) && isset($object[0]) && strlen($uri) > 0) {
            if (is_int($object[0])) {
                $uri = $this->buildUri($object, $uri);
            }
            
            $auxObject = $object;
            
            foreach ($auxObject as $key => $value) {
                if (strpos($uri, ":$key") === FALSE) {
                    continue;
                }
                
                $uri = str_replace(":$key", $value, $uri);
                unset($object[$key]);
            }
        }
        
        return $uri;
    }
    
}
