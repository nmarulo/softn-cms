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
    
    public function getAll(UserRequest $users = NULL): UserResponse {
        try {
            return $this->get($users);
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
    
    protected function getParseToClass(): string {
        return UserResponse::class;
    }
    
    protected function baseUri(): string {
        return 'dashboard/users';
    }
    
}
