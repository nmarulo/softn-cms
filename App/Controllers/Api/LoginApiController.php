<?php

namespace App\Controllers\Api;

use App\Facades\TokenFacade;
use App\Facades\UtilsFacade;
use App\Helpers\ConstHelper;
use App\Models\Users;
use App\Rest\Dto\UsersDTO;
use App\Rest\Requests\Users\UserRequest;
use App\Rest\Responses\Users\UserResponse;
use Lcobucci\JWT\Builder;
use Silver\Core\Bootstrap\Facades\Request;
use Silver\Core\Controller;

/**
 * LoginApi controller
 */
class LoginApiController extends Controller {
    
    /**
     * @return array
     * @throws \Exception
     */
    public function login() {
        $request = UserRequest::parseOf(Request::all());
        $user    = Users::where('user_login', '=', $request->userLogin)
                        ->first();
        
        //Si el usuario existe y su contraseña es igual
        if ($user && UtilsFacade::encryptVerify($request->userPassword, $user->user_password)) {
            $userDTO = UsersDTO::convertOfModel($user);
            TokenFacade::generate(function(Builder $builder) use ($userDTO) {
                $builder->set(ConstHelper::USER_ID_STR, $userDTO->id);
                
                return $builder;
            });
            
            return UserResponse::parseOf($userDTO->toArray())
                               ->toArray();
        }
        
        throw new \RuntimeException('Usuario y/o contraseña incorrecto(s).');
    }
    
}
