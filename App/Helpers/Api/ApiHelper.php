<?php
/**
 * ApiHelper.php
 */

namespace App\Helpers\Api;

use Silver\Core\Bootstrap\Facades\Request as RequestFacade;
use Silver\Http\Request;

/**
 * Class ApiHelper
 * @author Nicolás Marulanda P.
 */
abstract class ApiHelper {
    
    public static $HTTP_STATUS_OK                    = 200;
    
    public static $HTTP_STATUS_NO_CONTENT            = 204;
    
    public static $HTTP_STATUS_BAD_REQUEST           = 400;
    
    public static $HTTP_STATUS_UNAUTHORIZED          = 401;
    
    public static $HTTP_STATUS_NOT_FOUND             = 404;
    
    public static $HTTP_STATUS_INTERNAL_SERVER_ERROR = 500;
    
    public function getTokenHeader(Request $request = NULL) {
        if ($request) {
            return $request->header('AUTHORIZATION');
        }
        
        return RequestFacade::header('AUTHORIZATION');
    }
    
    protected function getValueByKey($response, $key, $default) {
        if (is_array($response) && array_key_exists($key, $response)) {
            return $response[$key];
        }
        
        return $default;
    }
    
    protected function objectToArray($object, $recursive = TRUE) {
        if (is_object($object)) {
            $object = (array)$object;
        }
        
        if ($recursive && is_array($object)) {
            $object = array_map(function($value) {
                return $this->objectToArray($value);
            }, $object);
        }
        
        return $object;
    }
    
    protected function headerToken() {
        return 'Authorization:' . $this->getToken();
    }
    
    protected abstract function getToken();
    
}
