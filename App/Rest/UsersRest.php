<?php
/**
 * UsersRest.php
 */

namespace App\Rest;

use App\Facades\Messages;
use App\Rest\Request\UserRequest;
use App\Rest\Response\UserResponse;

/**
 * Class UsersRest
 * @author Nicolás Marulanda P.
 */
class UsersRest extends RestCall {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getAll(UserRequest $request = NULL): UserResponse {
        try {
            return $this->get($request);
        } catch (\Exception $exception) {
            Messages::addDanger($exception->getMessage());
        }
        
        return new UserResponse();
    }
    
    public function getById(int $id): ?UserResponse {
        try {
            return $this->get(NULL, $id);
        } catch (\Exception $exception) {
            Messages::addDanger($exception->getMessage());
        }
        
        return new UserResponse();
    }
    
    public function create(UserRequest $request): ?UserResponse {
        try {
            return $this->post($request);
        } catch (\Exception $exception) {
            Messages::addDanger($exception->getMessage());
        }
        
        return new UserResponse();
    }
    
    public function update(int $id, UserRequest $request): ?UserResponse {
        try {
            return $this->put($id, $request);
        } catch (\Exception $exception) {
            Messages::addDanger($exception->getMessage());
        }
        
        return new UserResponse();
    }
    
    public function remove(int $id): bool {
        try {
            $this->delete($id);
            
            return TRUE;
        } catch (\Exception $exception) {
            Messages::addDanger($exception->getMessage());
        }
        
        return FALSE;
    }
    
    protected function getParseToClass(): string {
        return UserResponse::class;
    }
    
    protected function baseUri(): string {
        return 'dashboard/users';
    }
    
}
