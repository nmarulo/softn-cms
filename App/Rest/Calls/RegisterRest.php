<?php
/**
 * RegisterRest.php
 */

namespace App\Rest\Calls;

use App\Facades\MessagesFacade;
use App\Rest\Common\RestCall;
use App\Rest\Request\RegisterUserRequest;
use App\Rest\Response\UserResponse;

/**
 * Class RegisterRest
 * @author Nicolás Marulanda P.
 */
class RegisterRest extends RestCall {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function register(RegisterUserRequest $request): ?UserResponse {
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
        return 'register';
    }
    
}
