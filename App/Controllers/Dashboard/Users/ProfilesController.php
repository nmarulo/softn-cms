<?php
/**
 * softn-cms
 */

namespace App\Controllers\Dashboard\Users;

use App\Facades\Api\RequestApiFacade;
use App\Facades\MessagesFacade;
use App\Facades\Rest\PermissionsRestFacade;
use App\Facades\Rest\Users\ProfilesRestFacade;
use App\Facades\UtilsFacade;
use App\Helpers\Views\PermissionView;
use App\Rest\Requests\Users\ProfileRequest;
use App\Rest\Requests\Users\ProfilesRequest;
use App\Rest\Responses\Users\PermissionResponse;
use App\Rest\Responses\Users\ProfileResponse;
use Silver\Core\Bootstrap\Facades\Request;
use Silver\Core\Controller;
use Silver\Http\Redirect;
use Silver\Http\View;

/**
 * Class ProfilesController
 * @author Nicolás Marulanda P.
 */
class ProfilesController extends Controller {
    
    private $urlProfiles = '/dashboard/users/profiles';
    
    public function index($id = NULL) {
        $profile         = new ProfileResponse();
        $permissionsView = [];
        $profiles        = [];
        $pagination      = NULL;
        
        if (RequestApiFacade::isPostRequest()) {
            $profile         = ProfilesRestFacade::getById($id);
            $permissionsView = $this->getPermissionsView($profile->permissions);
        } else {
            $request            = new ProfilesRequest();
            $request->dataTable = UtilsFacade::getDataTable();
            $response           = ProfilesRestFacade::getAll($request);
            $pagination         = $response->pagination;
            $profiles           = $response->profiles;
            
            if (!is_array($profiles)) {
                $profiles = [];
            }
        }
        
        return View::make('dashboard.users.profiles.index')
                   ->with('profiles', $profiles)
                   ->withComponent($profile, 'profile')
                   ->withComponent($permissionsView, 'permissions')
                   ->withComponent($pagination, 'pagination');
    }
    
    public function form($id) {
        $request = ProfileRequest::parseOf(Request::all());
        $message = 'Perfil creado correctamente.';
        
        if ($id) {
            $message = 'Perfil actualizado correctamente.';
            ProfilesRestFacade::update($id, $request);
        } else {
            ProfilesRestFacade::create($request);
        }
        
        if (!ProfilesRestFacade::isError()) {
            MessagesFacade::addSuccess($message);
        }
        
        Redirect::to(URL . $this->urlProfiles);
    }
    
    public function delete($id) {
        if (ProfilesRestFacade::remove($id)) {
            MessagesFacade::addSuccess('Perfil borrado correctamente.');
        }
    }
    
    private function getPermissionsView(?array $profilePermissions): array {
        $permissions = PermissionsRestFacade::getAll()->permissions;
        
        if (!is_array($permissions)) {
            $permissions = [];
        }
        
        if (!is_array($profilePermissions)) {
            $profilePermissions = [];
        }
        
        return array_map(function(PermissionResponse $response) use ($profilePermissions) {
            $permissionId = array_map(function(PermissionResponse $profilePermission) {
                return $profilePermission->id;
            }, $profilePermissions);
            
            $permissionView          = PermissionView::parseOf($response->toArray());
            $permissionView->checked = array_search($response->id, $permissionId) !== FALSE;
            
            return $permissionView;
        }, $permissions);
    }
    
}
