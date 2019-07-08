<?php
/**
 * softn-cms
 */

namespace App\Controllers\Dashboard\Users;

use App\Facades\Api\RequestApiFacade;
use App\Facades\MessagesFacade;
use App\Facades\Rest\PermissionsRestFacade;
use App\Rest\Requests\Users\PermissionRequest;
use App\Rest\Responses\Users\PermissionResponse;
use Silver\Core\Bootstrap\Facades\Request;
use Silver\Core\Controller;
use Silver\Http\Redirect;
use Silver\Http\View;

/**
 * Class PermissionsController
 * @author Nicolás Marulanda P.
 */
class PermissionsController extends Controller {
    
    private $urlPermissions = '/dashboard/users/permissions';
    
    public function index($id = NULL) {
        $permission  = new PermissionResponse();
        $permissions = [];
        
        if (RequestApiFacade::isPostRequest()) {
            $permission = PermissionsRestFacade::getById($id);
        } else {
            $permissions = PermissionsRestFacade::getAll()->permissions;
            
            if (!is_array($permissions)) {
                $permissions = [];
            }
        }
        
        return View::make('dashboard.users.permissions.index')
                   ->with('permissions', $permissions)
                   ->withComponent($permission, 'permission');
    }
    
    public function form($id) {
        $request = PermissionRequest::parseOf(Request::all());
        $message = 'Permiso creado correctamente.';
        
        if ($id) {
            $message = 'Permiso actualizado correctamente.';
            PermissionsRestFacade::update($id, $request);
        } else {
            PermissionsRestFacade::create($request);
        }
        
        if (!PermissionsRestFacade::isError()) {
            MessagesFacade::addSuccess($message);
        }
        
        Redirect::to(URL . $this->urlPermissions);
    }
    
    public function delete($id) {
        if (PermissionsRestFacade::remove($id)) {
            MessagesFacade::addSuccess('Permiso borrado correctamente.');
        }
    }
    
}
