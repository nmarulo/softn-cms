<?php

namespace App\Facades;

use Silver\Support\Facade;

/**
 * @method static bool check(string $payload)
 * @method static mixed getCustomData(string $token, string $key)
 * @method static string getToken()
 * @method static generate(\Closure $closureClaim = FALSE)
 */
class TokenFacade extends Facade {
    
    protected static function getClass() {
        return 'App\Helpers\TokenHelper';
    }
    
}
