<?php

namespace App\Controllers\Dashboard;

use App\Facades\Api\RequestApiFacade;
use App\Facades\Messages;
use App\Facades\ModelFacade;
use App\Facades\Rest\UsersRestFacade;
use App\Facades\Utils;
use App\Models\Users;
use App\Rest\Request\UserRequest;
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
        $userRequest->dataTable = Utils::getDataTable();
        $response               = UsersRestFacade::getAll($userRequest);
        
        return View::make('dashboard.users.index')
                   ->with('users', $response->users)
                   ->withComponent($response->pagination, 'pagination');
    }
    
    public function form($id) {
        $userDTO = new \App\Rest\Dto\Users();
        
        if (RequestApiFacade::isPostRequest()) {
            $message = 'Usuario actualizado correctamente.';
            $request = Request::all();
            unset($request['uri']);
            
            if (empty($id)) {
                $message = 'Usuario creado correctamente';
                RequestApiFacade::post($this->urlUsers, $request);
            } else {
                $request['id'] = $id;
                RequestApiFacade::put($this->urlUsers, $request);
            }
            
            if (RequestApiFacade::isError()) {
                Messages::addDanger(RequestApiFacade::getMessage());
                $user = ModelFacade::arrayToObject($request, Users::class);
            } else {
                Messages::addSuccess($message);
                $user = ModelFacade::arrayToObject(RequestApiFacade::responseJsonDecode(), Users::class);
                
                if (empty($id)) {
                    Redirect::to(sprintf('%1$s/%2$s/form/%3$s', URL, $this->urlUsers, $user->id));
                }
            }
        } elseif ($id) {
            $userResponse = UsersRestFacade::getById($id);
            
            if (isset($userResponse->users[0])) {
                $userDTO = $userResponse->users[0];
            }
        }
        
        return View::make('dashboard.users.form')
                   ->with('user', $userDTO);
    }
    
    public function delete() {
        RequestApiFacade::delete($this->urlUsers, Request::all());
        
        if (RequestApiFacade::isError()) {
            Messages::addDanger(RequestApiFacade::getMessage());
        } else {
            Messages::addSuccess('Usuario borrado correctamente.');
        }
    }
}
