<?php
/**
 * UsersRest.php
 */

namespace App\Rest\Calls;

use App\Facades\MessagesFacade;
use App\Rest\Common\RestCall;
use App\Rest\Requests\Users\UserRequest;
use App\Rest\Responses\Users\UserResponse;
use App\Rest\Responses\Users\UsersResponse;

/**
 * Class UsersRest
 * @author Nicolás Marulanda P.
 */
class UsersRest extends RestCall {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getAll(UserRequest $request = NULL): UsersResponse {
        try {
            return $this->get($request, '', UsersResponse::class);
        } catch (\Exception $exception) {
            return new UsersResponse();
        }
    }
    
    public function getById(int $id): ?UserResponse {
        try {
            return $this->get(NULL, $id);
        } catch (\Exception $exception) {
            return new UserResponse();
        }
    }
    
    public function create(UserRequest $request): ?UserResponse {
        try {
            return $this->post($request);
        } catch (\Exception $exception) {
            return new UserResponse();
        }
    }
    
    public function update(int $id, UserRequest $request): ?UserResponse {
        try {
            return $this->put($id, $request);
        } catch (\Exception $exception) {
            return new UserResponse();
        }
    }
    
    public function remove(int $id): bool {
        try {
            $this->delete($id);
            
            return TRUE;
        } catch (\Exception $exception) {
            return FALSE;
        }
    }
    
    protected function baseClassParseTo(): string {
        return UserResponse::class;
    }
    
    protected function baseUri(): string {
        return 'dashboard/users';
    }
    
    protected function catchException(\Exception $exception): void {
        MessagesFacade::addDanger($exception->getMessage());
    }
    
}
