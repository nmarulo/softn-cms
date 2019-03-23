<?php
/**
 * UsersRest.php
 */

namespace App\Rest;

use App\Facades\Messages;
use App\Models\Users;
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
            return $this->get($users, '', UserResponse::class);
        } catch (\Exception $exception) {
            Messages::addDanger($exception->getMessage());
        }
        
        return new UserResponse();
    }
    
    protected function getClass(): string {
        return UserResponse::class;
    }
    
    protected function baseUri(): string {
        return 'dashboard/users';
    }
    
}
