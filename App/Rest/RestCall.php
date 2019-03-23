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
     * @param null   $object
     * @param string $uri
     * @param string $parseClass
     *
     * @return mixed
     * @throws \Exception
     */
    protected function get($object = NULL, string $uri = '', string $parseClass = '') {
        $uri = $this->buildUri($object, $uri);
        
        if (is_object($object) && $object instanceof ObjectToArray) {
            $object = $object->toArray();
        }
        
        return $this->makeCall('get', $object, $uri, $parseClass);
    }
    
    protected abstract function getClass(): string;
    
    protected abstract function baseUri(): string;
    
    /**
     * @param string $type
     * @param        $object
     * @param string $uri
     * @param string $parseClass
     *
     * @return mixed
     * @throws \Exception
     */
    private function makeCall(string $type, $object = NULL, string $uri = '', string $parseClass = '') {
        $uri = trim($this->baseUri(), '/') . "/$uri";
        
        $this->requestApiHelper->$type($uri, $object);
        
        if ($this->requestApiHelper->isError()) {
            throw new \Exception($this->requestApiHelper->getMessage());
        }
        
        $response = $this->requestApiHelper->responseJsonDecode();
        
        if (empty($parseClass)) {
            return $response;
        }
        
        return Utils::parseOf($response, $parseClass);
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
