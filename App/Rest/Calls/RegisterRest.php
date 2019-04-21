<?php
/**
 * RegisterRest.php
 */

namespace App\Rest\Calls;

use App\Facades\MessagesFacade;
use App\Rest\Common\RestCall;
use App\Rest\Requests\RegisterUserRequest;
use App\Rest\Responses\UserResponse;

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
            return NULL;
        }
    }
    
    protected function baseClassParseTo(): string {
        return UserResponse::class;
    }
    
    protected function baseUri(): string {
        return 'register';
    }
    
    protected function catchException(\Exception $exception): void {
        MessagesFacade::addDanger($exception->getMessage());
    }
}
