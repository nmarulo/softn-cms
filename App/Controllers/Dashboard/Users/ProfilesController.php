<?php
/**
 * softn-cms
 */

namespace App\Controllers\Dashboard\Users;

use App\Facades\Api\RequestApiFacade;
use App\Facades\MessagesFacade;
use App\Facades\Rest\Users\ProfilesRestFacade;
use App\Rest\Requests\Users\ProfileRequest;
use App\Rest\Responses\Users\ProfileResponse;
use App\Rest\Responses\Users\ProfilesResponse;
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
        $profile  = new ProfileResponse();
        $profiles = new ProfilesResponse();
        
        if (RequestApiFacade::isPostRequest()) {
            $profiles->profiles = [];
            $profile            = ProfilesRestFacade::getById($id);
        } else {
            $profiles = ProfilesRestFacade::getAll();
        }
        
        return View::make('dashboard.users.profiles.index')
                   ->with('profiles', $profiles->profiles)
                   ->withComponent($profile, 'profile');
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
    
}
