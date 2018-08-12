<?php

namespace App\Controllers\Api\Dashboard;

use App\Facades\Api\ResponseApiFacade;
use App\Facades\ModelFacade;
use App\Models\Users;
use Silver\Core\Bootstrap\Facades\Request;
use Silver\Core\Controller;

/**
 * UsersApi controller
 */
class UsersApiController extends Controller {
    
    public function get() {
        return ResponseApiFacade::makeResponse(function() {
            $userModel = ModelFacade::model(Users::class)
                                    ->search()
                                    ->pagination()
                                    ->sort();
            
            return [
                    'users'      => $userModel->all(),
                    'pagination' => json_encode($userModel->getPagination()),
            ];
        });
    }
    
    public function post() {
        return ResponseApiFacade::makeResponse(function() {
            return Request::all();
        });
    }
    
    public function put() {
        echo 'Method: put';
    }
    
    public function delete() {
        echo 'Method: delete';
    }
}
