<?php

namespace App\Facades;

use App\Helpers\MessagesHelper;
use Silver\Support\Facade;

/**
 * @method static addDanger(string $message)
 * @method static addSuccess(string $message)
 * @method static getMessages()
 */
class MessagesFacade extends Facade {
    
    protected static function getClass() {
        return MessagesHelper::class;
    }
    
}
