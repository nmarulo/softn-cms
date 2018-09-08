<?php

namespace App\Facades;

use Silver\Support\Facade;

/**
 * @method static addDanger(string $message)
 * @method static addSuccess(string $message)
 * @method static getMessages()
 */
class Messages extends Facade {
    
    protected static function getClass() {
        return 'App\Helpers\Messages';
    }
    
}
