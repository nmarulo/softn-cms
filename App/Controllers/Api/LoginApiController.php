<?php

namespace App\Controllers\Api;

use App\Facades\TokenFacade;
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
        if ($user && $this->checkPassword($user, $request)) {
            $userDTO = UsersDTO::convertOfModel($user);
            TokenFacade::generate(function(Builder $builder) use ($userDTO) {
                $builder->set('user_login', $userDTO->userLogin);
                
                return $builder;
            });
            
            return UserResponse::parseOf($userDTO->toArray())
                               ->toArray();
        } else {
            throw new \RuntimeException('Usuario y/o contraseña incorrecto(s).');
        }
    }
    
    private function checkPassword(Users $user, UserRequest $request): bool {
        //TODO: cifrar
        return $user->user_password == $request->userPassword;
    }
    
}
