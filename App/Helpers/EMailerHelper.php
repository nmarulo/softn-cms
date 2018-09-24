<?php

namespace App\Helpers;

use App\Facades\EMail\PHPMailerFacade;
use App\Helpers\EMail\PHPMailerHelper;
use App\Models\Users;

/**
 * Class EMailerHelper
 * @author Nicolás Marulanda P.
 */
class EMailerHelper {
    
    /**
     * @param Users $user
     *
     * @return PHPMailerHelper
     */
    public static function register($user) {
        $phpMailer = PHPMailerFacade::setUpSMTP();
        
        $values = [
                '#{user_login}'    => $user->user_login,
                '#{user_password}' => $user->user_password,
                '#{url}'           => URL . '/login',
        ];
        
        $phpMailer->addAddress($user->user_email);
        $phpMailer->setHtmlTemplate(file_get_contents(ROOT . 'App/Helpers/EMail/Templates/register.html'), $values);
        
        return $phpMailer;
    }
    
}
