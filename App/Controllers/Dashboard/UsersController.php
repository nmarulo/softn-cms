<?php

namespace App\Controllers\Dashboard;

use App\Facades\Api\RequestApiFacade;
use App\Facades\MessagesFacade;
use App\Facades\Rest\UsersRestFacade;
use App\Facades\UtilsFacade;
use App\Models\Users;
use App\Rest\Dto\UsersDTO;
use App\Rest\Requests\UserRequest;
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
            $message = 'Usuario actualizado correctamente.';
            $request = UserRequest::parseOf(Request::all());
            
            if (empty($id)) {
                $message      = 'Usuario creado correctamente';
                $userResponse = UsersRestFacade::create($request);
            } else {
                $userResponse = UsersRestFacade::update($id, $request);
            }
            
            if (isset($userResponse->users[0])) {
                MessagesFacade::addSuccess($message);
                
                if (empty($id)) {
                    Redirect::to(sprintf('%1$s/%2$s/form/%3$s', URL, $this->urlUsers, $userResponse->users[0]->id));
                }
            }
        } elseif ($id) {
            $userResponse = UsersRestFacade::getById($id);
        }
        
        if (isset($userResponse, $userResponse->users[0])) {
            $userDTO = $userResponse->users[0];
        }
        
        return View::make('dashboard.users.form')
                   ->with('user', $userDTO);
    }
    
    public function delete($id) {
        if (UsersRestFacade::remove($id)) {
            MessagesFacade::addSuccess('Usuario borrado correctamente.');
        }
    }
}
