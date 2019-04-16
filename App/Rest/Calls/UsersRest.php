<?php
/**
 * UsersRest.php
 */

namespace App\Rest\Calls;

use App\Facades\MessagesFacade;
use App\Rest\Common\RestCall;
use App\Rest\Requests\UserRequest;
use App\Rest\Responses\UsersResponse;

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
            return $this->get($request);
        } catch (\Exception $exception) {
            MessagesFacade::addDanger($exception->getMessage());
        }
        
        return new UsersResponse();
    }
    
    public function getById(int $id): ?UsersResponse {
        try {
            return $this->get(NULL, $id);
        } catch (\Exception $exception) {
            MessagesFacade::addDanger($exception->getMessage());
        }
        
        return new UsersResponse();
    }
    
    public function create(UserRequest $request): ?UsersResponse {
        try {
            return $this->post($request);
        } catch (\Exception $exception) {
            MessagesFacade::addDanger($exception->getMessage());
        }
        
        return new UsersResponse();
    }
    
    public function update(int $id, UserRequest $request): ?UsersResponse {
        try {
            return $this->put($id, $request);
        } catch (\Exception $exception) {
            MessagesFacade::addDanger($exception->getMessage());
        }
        
        return new UsersResponse();
    }
    
    public function remove(int $id): bool {
        try {
            $this->delete($id);
            
            return TRUE;
        } catch (\Exception $exception) {
            MessagesFacade::addDanger($exception->getMessage());
        }
        
        return FALSE;
    }
    
    protected function parseResponseTo(array $value) {
        return UsersResponse::parseOf($value);
    }
    
    protected function baseUri(): string {
        return 'dashboard/users';
    }
    
}
