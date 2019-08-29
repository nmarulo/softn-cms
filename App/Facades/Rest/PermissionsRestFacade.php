<?php
/**
 * softn-cms
 */

namespace App\Facades\Rest;

use App\Rest\Calls\PermissionsRest;
use App\Rest\Requests\Users\PermissionRequest;
use App\Rest\Requests\Users\PermissionsRequest;
use App\Rest\Responses\Users\PermissionResponse;
use App\Rest\Responses\Users\PermissionsResponse;
use Silver\Support\Facade;

/**
 * @method static PermissionsResponse getAll(PermissionsRequest $request = NULL)
 * @method static PermissionResponse getById(int $id)
 * @method static PermissionResponse create(PermissionRequest $request)
 * @method static PermissionResponse update(int $id, PermissionRequest $request)
 * @method static PermissionResponse updatePassword(int $id, PermissionRequest $request)
 * @method static bool remove(int $id)
 * @method static bool isError()
 * Class PermissionsRestFacade
 * @author Nicolás Marulanda P.
 */
class PermissionsRestFacade extends Facade {
    
    protected static function getClass() {
        return PermissionsRest::class;
    }
}
