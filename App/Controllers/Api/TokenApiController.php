<?php

namespace App\Controllers\Api;

use App\Facades\Api\RestCallFacade;
use App\Facades\TokenFacade;
use Lcobucci\JWT\Builder;
use Silver\Core\Controller;

/**
 * Token controller
 */
class TokenApiController extends Controller {
    
    /*
     * TODO: Este servicio solo debe ser llamado internamente.
     */
    public function generate() {
        return RestCallFacade::makeResponse(function($request) {
            $userLogin = $request['user_login'];
            TokenFacade::generate(function(Builder $builder) use ($userLogin) {
                $builder->set('user_login', $userLogin);
                
                return $builder;
            });
            
            return TokenFacade::getToken();
        });
    }
    
}
