<?php
/**
 * RequestApiHelper.php
 */

namespace App\Helpers\Api;

use App\Facades\Messages;
use Silver\Database\Model;
use Silver\Http\Curl;
use Silver\Http\Redirect;
use Silver\Http\Session;

/**
 * Class RequestApiHelper
 * @author Nicolás Marulanda P.
 */
class RequestApiHelper extends ApiHelper {
    
    private $httpRequestStatus;
    
    private $messageError;
    
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
    
    public function makePostRequest($dataToSend, $serviceName, $serviceMethod = '', $returnType = NULL) {
        return $this->makeRequest('POST', $dataToSend, $serviceName, $serviceMethod, $returnType);
    }
    
    public function makeRequest($type, $dataToSend, $serviceName, $serviceMethod, $returnType, $options = []) {
        $response = [];
        $url      = $this->createUrl($serviceName, $serviceMethod);
        //$this->headerToken();
        
        switch ($type) {
            case 'GET':
                $dataToSend          = (array)$dataToSend;
                $dataToSend['token'] = $this->getToken();
                $url                 .= $this->formatDataToSendRequestGet($dataToSend);
                $response            = Curl::get($url, NULL, $options);
                break;
            case 'POST':
                //Si "dataToSend" no es un array, dara error.
                $dataToSend          = $this->formatDataToSendRequestPost($dataToSend);
                $dataToSend['token'] = $this->getToken();
                $response            = json_decode(Curl::post($url, $dataToSend, $options), TRUE);
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
        
        $response = $this->objectToArray($response);
        
        $this->httpRequestStatus = $this->getValueByKey($response, 'http_status', 0);
        $this->messageError      = $this->getValueByKey($response, 'errors', []);
        //En caso de error no settea el token
        if (empty($this->messageError)) {
            $this->setToken($this->getValueByKey($response, 'token', ''));
        }
        
        if ($this->httpRequestStatus == 401) {
            Messages::addDanger($this->messageError['message']);
            Redirect::to(\URL . '/logout');
        }
        
        if (is_callable($returnType)) {
            return $returnType($this->getResponsePayload($response));
        }
        
        return $this->getResponsePayload($response);
    }
    
    private function createUrl($serviceName, $serviceMethod) {
        return sprintf('%1$s/api/%2$s/%3$s', URL, $serviceName, $serviceMethod);
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
        if ($dataToSend instanceof Model) {
            $dataToSend = $dataToSend->data();
        } elseif (is_object($dataToSend)) {
            throw new \RuntimeException('No puedes enviar este objeto en una petición POST.');
        }
        
        return [
                'payload' => $dataToSend,
        ];
    }
    
    public function setToken($token) {
        Session::set('token', $token);
    }
    
    private function getResponsePayload($response) {
        $response = $this->getValueByKey($response, 'payload', '');
        
        return $this->objectToArray($response);
    }
    
    public function makeGetRequest($dataToSend, $serviceName, $serviceMethod = '', $returnType = NULL) {
        if (is_array($dataToSend)) {
            //TODO: ERROR: solo al enviar, por get, "'uri' => '/dashboard/users'", por eso lo elimino, en caso de enviarlo.
            unset($dataToSend['uri']);
        }
        
        return $this->makeRequest('GET', $dataToSend, $serviceName, $serviceMethod, $returnType);
    }
    
}
