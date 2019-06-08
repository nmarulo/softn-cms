<?php
/**
 * RequestApiHelper.php
 */

namespace App\Helpers\Api;

use App\Facades\SessionFacade;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use Silver\Core\Bootstrap\Facades\Request;
use Silver\Core\Env;
use Silver\Database\Model;

/**
 * Class RequestApiHelper
 * @author Nicolás Marulanda P.
 */
class RequestApiHelper extends ApiHelper {
    
    /**
     * @var Client
     */
    private $client;
    
    /**
     * @var ResponseInterface
     */
    private $response;
    
    private $messageError;
    
    public function __construct() {
        $this->response     = NULL;
        $this->messageError = '';
        $stack              = new HandlerStack();
        $stack->setHandler(new CurlHandler());
        $stack->push(Middleware::mapResponse(function(ResponseInterface $response) {
            $decode = json_decode((string)$response->getBody(), TRUE);
            $response->getBody()
                     ->rewind();
            
            if (is_array($decode)) {
                $status  = "";
                $message = "";
                
                if (array_key_exists('errors', $decode)) {
                    $status  = $decode['errors']['status'];
                    $message = $decode['errors']['message'];
                } elseif (array_key_exists('data', $decode)) {
                    $status  = $decode['data']['code'];
                    $message = $decode['data']['message'];
                }
                
                $response = new Response($status, $response->getHeaders(), $response->getBody(), $response->getProtocolVersion(), $message);
            }
            
            return $response;
        }));
        $this->client = new Client([
                'base_uri' => $this->baseUri(),
                'handler'  => $stack,
        ]);
    }
    
    private function baseUri() {
        $uri    = URL;
        $apiUri = Env::get('api_base_uri') ? : '';
        
        if (!empty($apiUri)) {
            $apiUri = sprintf('/%1$s/', trim($apiUri, '/'));
        }
        
        return $uri . $apiUri;
    }
    
    public function __call($name, $arguments) {
        $uri = array_shift($arguments);
        $this->checkMethodGet($name, $uri, $arguments);
        $this->options($name, $arguments, $options);
        
        try {
            $this->response = $this->client->{$name}($uri, $options);
            $this->updateToken();
        } catch (\Exception $exception) {
            $this->messageError = $exception->getMessage();
        }
    }
    
    private function checkMethodGet($name, &$uri, &$arguments) {
        if ($this->isMethodGet($name) && !empty($arguments) && (is_numeric($arguments[0]) || is_string($arguments[0]))) {
            $uri = sprintf('%1$s/%2$s', trim($uri, '/'), array_shift($arguments));
        }
    }
    
    private function isMethodGet($method) {
        return array_search($method, [
                        'get',
                        'getAsync',
                ]) !== FALSE;
    }
    
    private function options($typeRequest, $arguments, &$options) {
        $options = $options ? : [];
        $default = [
                RequestOptions::HEADERS => [
                        'Accept'        => 'application/json',
                        'Authorization' => $this->getToken(),
                ],
                RequestOptions::TIMEOUT => 5,
        ];
        
        if (!empty($arguments)) {
            $shift = array_shift($arguments);
            
            if (is_null($shift)) {
                $shift = [];
            }
            
            if ($this->isMethodGet($typeRequest)) {
                if (is_array($shift)) {
                    $options[RequestOptions::QUERY] = $shift;
                }
            } else {
                $options[RequestOptions::FORM_PARAMS] = $this->formatDataToSendRequestPost($shift);
            }
            
            $shift   = array_shift($arguments);
            $options += is_array($shift) ? $shift : [];
        }
        
        $options += $default;
    }
    
    public function getToken() {
        return SessionFacade::getToken();
    }
    
    private function formatDataToSendRequestPost($dataToSend) {
        if ($dataToSend instanceof Model) {
            $dataToSend = $dataToSend->data();
        } elseif (is_object($dataToSend)) {
            $dataToSend = $this->objectToArray($dataToSend);
        }
        
        return $dataToSend;
    }
    
    private function updateToken() {
        if ($this->isError()) {
            return;
        }
        
        $token = $this->response->getHeader('Authorization');
        
        if (isset($token[0])) {
            SessionFacade::setToken($token[0]);
        }
    }
    
    public function isError() {
        return is_null($this->response) || $this->getStatusCode() >= self::$HTTP_STATUS_BAD_REQUEST;
    }
    
    public function getStatusCode() {
        if (is_null($this->response)) {
            return 0;
        }
        
        return $this->response->getStatusCode();
    }
    
    public function getResponse() {
        return $this->response;
    }
    
    public function responseJsonDecode($assoc = TRUE) {
        return \GuzzleHttp\json_decode($this->getBody(), $assoc);
    }
    
    public function getBody() {
        return $this->response->getBody()
                              ->getContents();
    }
    
    public function getMessage() {
        return empty($this->response) ? $this->messageError : $this->response->getReasonPhrase();
    }
    
    public function isGetRequest() {
        return $this->isRequestMethod('GET');
    }
    
    private function isRequestMethod($requestMethodName) {
        return Request::method() == strtolower($requestMethodName);
    }
    
    public function isPostRequest() {
        return $this->isRequestMethod('POST');
    }
}
