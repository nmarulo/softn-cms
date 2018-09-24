<?php

namespace App\Facades\EMail;

use App\Helpers\EMail\PHPMailerHelper;
use Silver\Support\Facade;

/**
 * @method static PHPMailerHelper setUpSMTP()
 */
class PHPMailerFacade extends Facade {
    
    protected static function getClass() {
        return 'App\Helpers\EMail\PHPMailerHelper';
    }
    
}
