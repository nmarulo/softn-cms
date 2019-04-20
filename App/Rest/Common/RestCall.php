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
     * RestCall constructor.
     */
    protected function __construct() {
        $this->requestApiHelper = new RequestApiHelper();
    }
    
    /**
     * @param mixed  $object
     * @param string $uri
     *
     * @return mixed
     * @throws \Exception
     */
    protected function get($object = NULL, string $uri = '') {
        $uri = $this->buildUri($object, $uri);
        
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
     *
     * @return mixed
     * @throws \Exception
     */
    protected function put(int $id, $object) {
        return $this->makeCall('put', $object, $id);
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
    
    /**
     * @param array $value
     *
     * @return mixed
     */
    protected abstract function parseResponseTo(array $value);
    
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
        
        if (is_object($object) && (UtilsFacade::isUseTrait($object, ObjectToArray::class) || $object instanceof ObjectToArray)) {
            $object = $object->toArray();
        }
        
        try {
            $this->requestApiHelper->$type($uri, $object);
            
            if ($this->requestApiHelper->isError()) {
                throw new \Exception($this->requestApiHelper->getMessage());
            }
            
            $response = $this->requestApiHelper->responseJsonDecode();
            
            return $this->parseResponseTo($response);
        } catch (\Exception $exception) {
            $this->catchException($exception);
            throw $exception;
        }
    }
    
    private function buildUri(&$object, string $uri = ''): string {
        $uri = trim($uri, '/');
        
        if (is_int($object)) {
            $uri = "$uri/$object";
        } elseif (is_array($object) && isset($object[0])) {
            if (is_int($object[0])) {
                $uri = $this->buildUri($object, $uri);
            }
            
            $auxObject = $object;
            
            foreach ($auxObject as $key => $value) {
                $uri = str_replace(":$key", $value, $uri);
                unset($object[$key]);
            }
        }
        
        return $uri;
    }
    
}
