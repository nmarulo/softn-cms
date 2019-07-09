<?php
/**
 * softn-cms
 */

namespace App\Rest\Calls\Users;

use App\Facades\MessagesFacade;
use App\Rest\Common\RestCall;
use App\Rest\Requests\Users\ProfileRequest;
use App\Rest\Requests\Users\ProfilesRequest;
use App\Rest\Responses\Users\ProfileResponse;
use App\Rest\Responses\Users\ProfilesResponse;

/**
 * Class ProfilesRest
 * @author Nicolás Marulanda P.
 */
class ProfilesRest extends RestCall {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getAll(ProfilesRequest $request = NULL): ProfilesResponse {
        try {
            return $this->get($request, '', ProfilesResponse::class);
        } catch (\Exception $exception) {
            return new ProfilesResponse();
        }
    }
    
    public function getById(int $id): ?ProfileResponse {
        try {
            return $this->get(NULL, $id);
        } catch (\Exception $exception) {
            return new ProfileResponse();
        }
    }
    
    public function create(ProfileRequest $request): ?ProfileResponse {
        try {
            return $this->post($request);
        } catch (\Exception $exception) {
            return new ProfileResponse();
        }
    }
    
    public function update(int $id, ProfileRequest $request): ?ProfileResponse {
        try {
            return $this->put($id, $request);
        } catch (\Exception $exception) {
            return new ProfileResponse();
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
        return ProfileResponse::class;
    }
    
    protected function baseUri(): string {
        return 'dashboard/profiles';
    }
    
    protected function catchException(\Exception $exception): void {
        MessagesFacade::addDanger($exception->getMessage());
    }
}
