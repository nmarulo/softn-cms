<?php
/**
 * RestCall.php
 */

namespace App\Rest;

use App\Facades\Utils;
use App\Helpers\Api\RequestApiHelper;
use App\Rest\Common\ObjectToArray;

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
        
        if (is_object($object) && $object instanceof ObjectToArray) {
            $object = $object->toArray();
        }
        
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
    
    protected abstract function getParseToClass(): string;
    
    protected abstract function baseUri(): string;
    
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
        
        $this->requestApiHelper->$type($uri, $object);
        
        if ($this->requestApiHelper->isError()) {
            throw new \Exception($this->requestApiHelper->getMessage());
        }
        
        if (empty($this->getParseToClass())) {
            throw new \Exception("Parse To Class no definida.");
        }
        
        $response = $this->requestApiHelper->responseJsonDecode();
        
        return Utils::parseOf($response, $this->getParseToClass());
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
