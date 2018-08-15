<?php
/**
 * RequestApiHelper.php
 */

namespace App\Helpers\Api;

use App\Facades\Messages;
use Silver\Core\Bootstrap\Facades\Request;
use Silver\Database\Model;
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
        $header  = [];
        $url     = $this->createUrl($serviceName, $serviceMethod);
        $options += [CURLOPT_HTTPHEADER => [$this->headerToken()]];
        
        switch ($type) {
            case 'GET':
                $dataToSend = (array)$dataToSend;
                $url        .= $this->formatDataToSendRequestGet($dataToSend);
                $response   = $this->get($url, $header, $options);
                break;
            case 'POST':
                //Si "dataToSend" no es un array, dara error.
                $dataToSend = $this->formatDataToSendRequestPost($dataToSend);
                $response   = $this->post($url, $dataToSend, $header, $options);
                break;
            case 'PUT':
                $dataToSend = $this->formatDataToSendRequestPost($dataToSend);
                $response   = $this->put($url, $dataToSend, $header, $options);
                break;
            case 'DELETE':
                $dataToSend = $this->formatDataToSendRequestPost($dataToSend);
                $response   = $this->delete($url, $dataToSend, $header, $options);
                break;
            default:
                throw new \RuntimeException('Tipo de petición no valido');
                break;
        }
        
        $response                = $this->objectToArray($response);
        $this->httpRequestStatus = $this->getValueByKey($response, 'http_status', 0);
        $this->messageError      = $this->getValueByKey($response, 'errors', []);
        
        //En caso de error no settea el token
        if (empty($this->messageError)) {
            $this->setToken($this->getValueByKey($header, 'Authorization', ''));
        }
        
        if ($this->httpRequestStatus > self::$HTTP_STATUS_NO_CONTENT && !Request::ajax()) {
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
    
    private function get($url, &$header = [], $options = []) {
        return $this->curl($url, $header, $options);
    }
    
    private function curl($url, &$header = [], $options = []) {
        $defaults = [
                CURLOPT_URL            => $url,
                CURLOPT_HEADER         => 1,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_TIMEOUT        => 4,
        ];
        
        $ch = curl_init();
        curl_setopt_array($ch, ($options + $defaults));
        
        if ($result = curl_exec($ch)) {
            $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $headerTmp  = substr($result, 0, $headerSize);
            $headerTmp  = preg_split('/\r\n/', $headerTmp);
            $headerTmp  = array_filter($headerTmp, function($value) {
                return !empty($value);
            });
            
            foreach ($headerTmp as $value) {
                $split = preg_split('/:\s{1}/', $value);
                
                if (count($split) == 1) {
                    $header[] = $split[0];
                    continue;
                }
                
                $header[$split[0]] = $split[1];
            }
            
            $result = substr($result, $headerSize);
            //$httpCode    = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        } else {
            trigger_error(curl_error($ch));
        }
        
        curl_close($ch);
        
        return json_decode($result, TRUE);
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
    
    private function post($url, $postFields = [], &$header = [], $options = []) {
        return $this->curlBody($url, $postFields, $header, $options);
    }
    
    private function curlBody($url, $postFields = [], &$header = [], $options = []) {
        $options += [
                CURLOPT_POST          => 1,
                CURLOPT_POSTFIELDS    => http_build_query($postFields),
                CURLOPT_FRESH_CONNECT => 1,
                CURLOPT_FORBID_REUSE  => 1,
        ];
        
        return $this->curl($url, $header, $options);
    }
    
    private function put($url, $postFields = [], &$header = [], $options = []) {
        $options += [CURLOPT_CUSTOMREQUEST => 'PUT'];
        
        return $this->curlBody($url, $postFields, $header, $options);
    }
    
    private function delete($url, $postFields = [], &$header = [], $options = []) {
        $options += [CURLOPT_CUSTOMREQUEST => 'DELETE'];
        
        return $this->curlBody($url, $postFields, $header, $options);
    }
    
    public function setToken($token) {
        Session::set('token', $token);
    }
    
    private function getResponsePayload($response) {
        $response = $this->getValueByKey($response, 'payload', '');
        
        return $this->objectToArray($response);
    }
    
    public function getToken() {
        return Session::get('token');
    }
    
    public function makeGetRequest($dataToSend, $serviceName, $serviceMethod = '', $returnType = NULL) {
        if (is_array($dataToSend)) {
            //TODO: ERROR: solo al enviar, por get, "'uri' => '/dashboard/users'", por eso lo elimino, en caso de enviarlo.
            unset($dataToSend['uri']);
        }
        
        return $this->makeRequest('GET', $dataToSend, $serviceName, $serviceMethod, $returnType);
    }
    
}
