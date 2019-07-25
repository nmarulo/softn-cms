<?php

namespace App\Controllers\Dashboard;

use App\Facades\Api\RequestApiFacade;
use App\Facades\MessagesFacade;
use App\Facades\Rest\Settings\GrAvatarSettingsFormRestFacade;
use App\Facades\Rest\Users\ProfilesRestFacade;
use App\Facades\Rest\UsersRestFacade;
use App\Facades\UtilsFacade;
use App\Helpers\GravatarHelper;
use App\Helpers\Views\ProfileView;
use App\Rest\Dto\UsersDTO;
use App\Rest\Requests\Users\UserRequest;
use App\Rest\Responses\Users\ProfileResponse;
use App\Rest\Responses\Users\UserResponse;
use Silver\Core\Bootstrap\Facades\Request;
use Silver\Core\Controller;
use Silver\Http\Redirect;
use Silver\Http\View;

/**
 * users controller
 */
class UsersController extends Controller {
    
    /**
     * @var string
     */
    private $urlUsers = 'dashboard/users';
    
    public function index() {
        $userRequest            = new UserRequest();
        $userRequest->dataTable = UtilsFacade::getDataTable();
        $response               = UsersRestFacade::getAll($userRequest);
        $users                  = $response->users;
        
        if (!is_array($users)) {
            $users = [];
        }
        
        return View::make('dashboard.users.index')
                   ->with('users', $users)
                   ->withComponent($response->pagination, 'pagination');
    }
    
    public function form($id) {
        $userResponse = new UserResponse();
        
        if (RequestApiFacade::isPostRequest()) {
            $request = UserRequest::parseOf(Request::all());
            $gravatar = $this->getGrAvatar($request->userEmail);
            $request->userUrlImage = $gravatar->get();
            $this->create($id, $request, $userResponse);
            $this->update($id, $request, $userResponse);
            $this->redirectForm($userResponse->id);
        } elseif ($id) {
            $userResponse = UsersRestFacade::getById($id);
            
            if (UsersRestFacade::isError()) {
                $this->redirectForm();
            }
            
            $gravatar = $this->getGrAvatar($userResponse->userEmail);
            $gravatar->setSize(GravatarHelper::DEFAULT_SIZE_128);
            $userResponse->userUrlImage = $gravatar->get();
        }
        
        $profilesView = $this->getProfileView($userResponse->profile);
        
        return View::make('dashboard.users.form')
                   ->with('user', $userResponse)
                   ->with('profilesView', $profilesView)
                   ->withComponent($userResponse, 'user');
    }
    
    public function delete($id) {
        if (UsersRestFacade::remove($id)) {
            MessagesFacade::addSuccess('Usuario borrado correctamente.');
        }
    }
    
    public function formPassword($id) {
        $request = UserRequest::parseOf(Request::all());
        UsersRestFacade::updatePassword($id, $request);
        
        if (!UsersRestFacade::isError()) {
            MessagesFacade::addSuccess('Contraseña actualizada correctamente.');
        }
        
        $this->redirectForm($id);
    }
    
    private function create($id, UserRequest $request, UsersDTO &$usersDTO) {
        if ($id) {
            return;
        }
    
        $usersDTO = UsersRestFacade::create($request);
        
        if (!UsersRestFacade::isError()) {
            MessagesFacade::addSuccess('Usuario creado correctamente.');
        }
    }
    
    private function update($id, UserRequest $request, UsersDTO &$usersDTO) {
        if (!$id) {
            return;
        }
        
        $usersDTO = UsersRestFacade::update($id, $request);
        
        if (!UsersRestFacade::isError()) {
            MessagesFacade::addSuccess('Usuario actualizado correctamente.');
        }
    }
    
    private function redirectForm($id = '') {
        Redirect::to(sprintf('%1$s/%2$s/form/%3$s', URL, $this->urlUsers, $id));
    }
    
    private function getProfileView(?ProfileResponse $profileResponse): array {
        $profiles  = ProfilesRestFacade::getAll()->profiles;
        $profileId = $profileResponse ? $profileResponse->id : NULL;
        
        if (!is_array($profiles)) {
            $profiles = [];
        }
        
        return array_map(function(ProfileResponse $response) use ($profileId) {
            $profileView           = ProfileView::parseOf($response->toArray());
            $profileView->selected = $profileView->id == $profileId;
            
            return $profileView;
        }, $profiles);
    }
    
    private function getGrAvatar(string $email = ''): GravatarHelper {
        $gravatarResponse = GrAvatarSettingsFormRestFacade::getForm();
        
        return new GravatarHelper($gravatarResponse, $email);
    }
    
}
