<?php
/**
 * RestCallHelper.php
 */

namespace App\Helpers\Api;

use App\Facades\Utils;
use Silver\Core\Bootstrap\Facades\Request;
use Silver\Core\Env;
use Silver\Http\Curl;
use Silver\Database\Model;
use Silver\Http\Session;

/**
 * Class RestCallHelper
 * @author Nicolás Marulanda P.
 */
class RestCallHelper {
    
    private static $HTTP_STATUS_OK                    = 200;
    
    private static $HTTP_STATUS_NO_CONTENT            = 204;
    
    private static $HTTP_STATUS_BAD_REQUEST           = 400;
    
    private static $HTTP_STATUS_INTERNAL_SERVER_ERROR = 500;
    
    private        $httpRequestStatus;
    
    private        $messageError;
    
    /**
     * RestCallHelper constructor.
     */
    public function __construct() {
        $this->httpRequestStatus = 0;
        $this->messageError      = [];
    }
    
    public function getHttpRequestStatus() {
        return $this->httpRequestStatus;
    }
    
    /**
     * @return array
     */
    public function getMessageError() {
        return $this->messageError;
    }
    
    public function makePostRequest($dataToSend, $serviceName, $serviceMethod, $returnType) {
        return $this->makeRequest('POST', $dataToSend, $serviceName, $serviceMethod, $returnType);
    }
    
    public function makeRequest($type, $dataToSend, $serviceName, $serviceMethod, $returnType, $options = []) {
        $response = [];
        $url      = $this->createUrl($serviceName, $serviceMethod);
        $this->headerToken();
        
        switch ($type) {
            case 'GET':
                $url      .= $this->formatDataToSendRequestGet((array)$dataToSend);
                $response = Curl::get($url, NULL, $options);
                break;
            case 'POST':
                $dataToSend = $this->formatDataToSendRequestPost((array)$dataToSend);
                $response   = Curl::post($url, $dataToSend, $options);
                break;
            case 'PUT':
                //$response = Curl::put($url);
                break;
            case 'DELETE':
                //$response = Curl::delete($url);
                break;
            default:
                throw new \RuntimeException('Tipo de petición no valido');
                break;
        }
        
        $this->httpRequestStatus = $this->getValueByKey($response, 'http_status', 0);
        $this->messageError      = $this->getValueByKey($response, 'errors', []);
        //TODO: generar nuevo token. cuando se realiza la petición, la respuesta podría retornar un nuevo token en el encabezado
        $this->setToken('');
        
        if (is_callable($returnType)) {
            return $returnType($response);
        }
        
        return $response;
    }
    
    private function createUrl($serviceName, $serviceMethod) {
        return sprintf('%1$s/api/%2$s/%3$s', URL, $serviceName, $serviceMethod);
    }
    
    private function headerToken() {
        header('token:' . $this->getToken());
    }
    
    public function getToken() {
        return Session::get('token', Request::input('token'));
    }
    
    private function formatDataToSendRequestGet($dataToSend) {
        if (!empty($dataToSend)) {
            $dataToSend = array_map(function($key, $value) {
                return sprintf('%1$s=%2$s', $key, $value);
            }, array_keys($dataToSend), $dataToSend);
            
            return '?' . implode('&', $dataToSend);
        }
        
        return '';
    }
    
    private function formatDataToSendRequestPost($dataToSend) {
        return [
                'payload' => $dataToSend,
        ];
    }
    
    private function getValueByKey($response, $key, $default) {
        if (is_array($response) && array_key_exists($key, $response)) {
            return $response[$key];
        }
        
        return $default;
    }
    
    public function setToken($token) {
    
    }
    
    public function makeGetRequest($serviceName, $serviceMethod, $returnType, $dataToSend = []) {
        return $this->makeRequest('GET', $dataToSend, $serviceName, $serviceMethod, $returnType);
    }
    
    public function makeResponse($callback) {
        header('Content-Type: application/json');
        $response = [];
        $request  = $this->getDataRequest();//TODO: comprobar el tipo
        
        try {
            if (!is_callable($callback)) {
                throw new \Exception('El método no se puede ejecutar.');
            }
            
            if (!Utils::isGetRequest() && empty($request)) {
                $response = $this->createResponseFormat(self::$HTTP_STATUS_BAD_REQUEST, 'Faltan datos en la petición.');
            } else {
                $dataToSend = $callback($request);
                
                if ($dataToSend === NULL) {
                    $response = $this->createResponseFormat(self::$HTTP_STATUS_NO_CONTENT);
                } else {
                    $response = $this->createResponseFormat(self::$HTTP_STATUS_OK, $dataToSend);
                }
            }
        } catch (\Exception $ex) {
            $response = $this->createResponseFormat(self::$HTTP_STATUS_INTERNAL_SERVER_ERROR, $ex->getMessage());
        }
        
        return $response;
    }
    
    public function getDataRequest() {
        return Request::input('payload');
    }
    
    private function createResponseFormat($httpStatus, $dataToSend = FALSE) {
        $payload = [
                'debug'       => Env::get('api_debug', FALSE),
                'version'     => Env::get('api_version', '0.0.0'),
                'http_status' => $httpStatus,
        ];
        
        switch ($httpStatus) {
            case self::$HTTP_STATUS_OK:
                $payload['payload']       = $this->formatDataToSendResponse($dataToSend);
                $payload['request_limit'] = Env::get('api_request_limit', 25);
                break;
            case self::$HTTP_STATUS_NO_CONTENT:
                $payload['request_limit'] = Env::get('api_request_limit', 25);
                break;
            default:
                $payload['errors'] = [
                        'message' => $dataToSend,
                        'check'   => TRUE,
                ];
                break;
        }
        
        return $payload;
    }
    
    private function formatDataToSendResponse($dataToSend) {
        if ($dataToSend instanceof Model) {
            $hidden = $dataToSend->getHidden();
            $data   = $dataToSend->data();
            
            $dataToSend = array_filter($data, function($key) use ($hidden) {
                return !in_array($key, $hidden);
            }, ARRAY_FILTER_USE_KEY);
        } elseif (is_array($dataToSend)) {
            foreach ($dataToSend as &$value) {
                $value = $this->formatDataToSendResponse($value);
            }
        }
        
        return $dataToSend;
    }
    
}
