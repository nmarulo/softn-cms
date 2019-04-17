<?php

namespace App\Controllers\Api;

use App\Facades\UtilsFacade;
use App\Helpers\EMailerHelper;
use App\Models\Users;
use App\Rest\Dto\UsersDTO;
use App\Rest\Requests\RegisterUserRequest;
use App\Rest\Responses\UserResponse;
use Silver\Core\Bootstrap\Facades\Request;
use Silver\Core\Controller;

/**
 * RegisterApi controller
 */
class RegisterApiController extends Controller {
    
    /**
     * @return array
     * @throws \Exception
     */
    public function register() {
        $request = RegisterUserRequest::parseOf(Request::all());
        $user    = Users::where('user_login', '=', $request->userLogin)
                        ->orWhere('user_email', '=', $request->userEmail)
                        ->first();
        
        if ($user) {
            throw new \RuntimeException('No puedes registrar este usuario.');
        }
        
        if ($request->userPassword != $request->userPasswordRe) {
            throw new \RuntimeException('Las contraseñas no son iguales.');
        }
        
        $request->userRegistered = UtilsFacade::dateNow();
        $user                    = UsersDTO::convertToModel($request, FALSE);
        $userDTO                 = UsersDTO::convertOfModel($user->save());
        $response                = UserResponse::parseOf($userDTO->toArray());
        
        try {
            EMailerHelper::register($user)
                         ->send('Registro de usuario');
        } catch (\Exception $exception) {
            //TODO: log
        }
        
        return $response->toArray();
    }
    
}
