<?php
/**
 * Api.php
 */

namespace App\Facades;

use Silver\Support\Facade;

/**
 * Class Api
 * @author Nicolás Marulanda P.
 */
class Api extends Facade {
    
    protected static function getClass() {
        return 'App\Helpers\Api';
    }
}
