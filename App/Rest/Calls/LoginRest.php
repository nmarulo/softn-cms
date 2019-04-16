<?php
/**
 * LoginRest.php
 */

namespace App\Rest\Calls;

use App\Facades\MessagesFacade;
use App\Rest\Common\RestCall;
use App\Rest\Request\UserRequest;
use App\Rest\Response\UserResponse;

/**
 * Class LoginRest
 * @author Nicolás Marulanda P.
 */
class LoginRest extends RestCall {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function login(UserRequest $request): ?UserResponse {
        try {
            return $this->post($request);
        } catch (\Exception $exception) {
            MessagesFacade::addDanger($exception->getMessage());
        }
        
        return NULL;
    }
    
    protected function parseResponseTo(array $value) {
        return UserResponse::parseOf($value);
    }
    
    protected function baseUri(): string {
        return 'login';
    }
    
}
