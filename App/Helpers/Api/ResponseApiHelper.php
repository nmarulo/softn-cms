<?php
/**
 * ResponseApiHelper.php
 */

namespace App\Helpers\Api;

use App\Facades\Api\RequestApiFacade;
use App\Facades\TokenFacade;
use Silver\Database\Model;
use Silver\Core\Bootstrap\Facades\Request as RequestFacade;
use Silver\Http\Request;

/**
 * Class ResponseApiHelper
 * @author Nicolás Marulanda P.
 */
class ResponseApiHelper extends ApiHelper {
    
    public function makeResponse($callback) {
        header('Content-Type: application/json');
        $response = [];
        $request  = $this->getRequest();//recibe un array
        
        try {
            if (!is_callable($callback)) {
                throw new \Exception('El método no se puede ejecutar.');
            }
            
            if (!RequestApiFacade::isGetRequest() && empty($request)) {
                $response = $this->createResponseFormat(self::$HTTP_STATUS_BAD_REQUEST, 'Faltan datos en la petición.');
            } else {
                $dataToSend = $callback();
                
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
    
    public function getRequest() {
        return RequestFacade::all();
    }
    
    public function createResponseFormat($httpStatus, $dataToSend = []) {
        header('Authorization:' . $this->getToken());
        $payload = [];
        
        switch ($httpStatus) {
            case self::$HTTP_STATUS_OK:
                $payload = $this->formatDataToSendResponse($dataToSend);
                break;
            case self::$HTTP_STATUS_NO_CONTENT:
                break;
            default:
                $payload['errors'] = [
                        'message' => $dataToSend,
                        'status'  => $httpStatus,
                ];
                break;
        }
        
        return $payload;
    }
    
    public function getToken() {
        return TokenFacade::getToken();
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
    
    public function getTokenHeader(Request $request = NULL) {
        if ($request) {
            return $request->header('AUTHORIZATION');
        }
        
        return RequestFacade::header('AUTHORIZATION');
    }
    
}
