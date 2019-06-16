<?php
/**
 * softn-cms
 */

namespace App\Rest\Calls;

use App\Facades\MessagesFacade;
use App\Rest\Common\RestCall;
use App\Rest\Requests\Users\PermissionRequest;
use App\Rest\Requests\Users\PermissionsRequest;
use App\Rest\Responses\Users\PermissionResponse;
use App\Rest\Responses\Users\PermissionsResponse;

/**
 * Class PermissionsRest
 * @author Nicolás Marulanda P.
 */
class PermissionsRest extends RestCall {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getAll(PermissionsRequest $request = NULL): PermissionsResponse {
        try {
            return $this->get($request, '', PermissionsResponse::class);
        } catch (\Exception $exception) {
            return new PermissionsResponse();
        }
    }
    
    public function getById(int $id): ?PermissionResponse {
        try {
            return $this->get(NULL, $id);
        } catch (\Exception $exception) {
            return new PermissionResponse();
        }
    }
    
    public function create(PermissionRequest $request): ?PermissionResponse {
        try {
            return $this->post($request);
        } catch (\Exception $exception) {
            return new PermissionResponse();
        }
    }
    
    public function update(int $id, PermissionRequest $request): ?PermissionResponse {
        try {
            return $this->put($id, $request);
        } catch (\Exception $exception) {
            return new PermissionResponse();
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
        return PermissionResponse::class;
    }
    
    protected function baseUri(): string {
        return 'dashboard/permissions';
    }
    
    protected function catchException(\Exception $exception): void {
        MessagesFacade::addDanger($exception->getMessage());
    }
}
