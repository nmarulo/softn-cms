<?php

namespace App\Controllers\Dashboard;

use App\Facades\Api\RequestApiFacade;
use App\Facades\MessagesFacade;
use App\Facades\Rest\UsersRestFacade;
use App\Facades\UtilsFacade;
use App\Rest\Dto\UsersDTO;
use App\Rest\Requests\Users\UserRequest;
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
        $userDTO = new UsersDTO();
        
        if (RequestApiFacade::isPostRequest()) {
            $request = UserRequest::parseOf(Request::all());
            $this->create($id, $request);
            $this->update($id, $request, $userDTO);
        } elseif ($id) {
            $userDTO = UsersRestFacade::getById($id);
            
            if (UsersRestFacade::isError()) {
                $this->redirectForm();
            }
        }
        
        return View::make('dashboard.users.form')
                   ->with('user', $userDTO)
                   ->withComponent($userDTO, 'user');
    }
    
    public function delete($id) {
        if (UsersRestFacade::remove($id)) {
            MessagesFacade::addSuccess('Usuario borrado correctamente.');
        }
    }
    
    private function create($id, UserRequest $request) {
        if ($id) {
            return;
        }
        
        $userResponse = UsersRestFacade::create($request);
        
        if (!UsersRestFacade::isError()) {
            MessagesFacade::addSuccess('Usuario creado correctamente.');
            $this->redirectForm($userResponse->id);
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
    
}
