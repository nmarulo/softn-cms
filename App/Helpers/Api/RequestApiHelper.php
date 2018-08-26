<?php
/**
 * RequestApiHelper.php
 */

namespace App\Helpers\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use Silver\Core\Env;
use Silver\Database\Model;
use Silver\Http\Session;

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
            
            if (is_array($decode) && array_key_exists('errors', $decode)) {
                $error = $decode['errors'];
                
                $response = new Response($error['status'], $response->getHeaders(), $response->getBody(), $response->getProtocolVersion(), $error['message']);
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
        $this->options($name, $arguments, $options);
        
        try {
            $this->response = $this->client->{$name}($uri, $options);
            $this->updateToken();
        } catch (\Exception $exception) {
            $this->messageError = $exception->getMessage();
        }
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
            
            if ($typeRequest == 'get') {
                $options[RequestOptions::QUERY] = $shift;
            } else {
                $options[RequestOptions::FORM_PARAMS] = $this->formatDataToSendRequestPost($shift);
            }
            
            $shift   = array_shift($arguments);
            $options += is_array($shift) ? $shift : [];
        }
        
        $options += $default;
    }
    
    public function getToken() {
        return Session::get('token');
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
        Session::set('token', $token);
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
    
}
