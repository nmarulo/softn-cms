<?php
/**
 * LogoutController.php
 */

namespace SoftnCMS\controllers\login;

use SoftnCMS\models\managers\LoginManager;
use SoftnCMS\models\managers\OptionsManager;
use SoftnCMS\util\Messages;
use SoftnCMS\util\Util;

/**
 * Class LogoutController
 * @author Nicolás Marulanda P.
 */
class LogoutController {
    
    public function index() {
        if (LoginManager::isLogin()) {
            unset($_SESSION[SESSION_USER]);
            
            if (isset($_COOKIE[COOKIE_USER_REMEMBER])) {
                setcookie(COOKIE_USER_REMEMBER, '', time() - 3600, '/');
                //Tiempo de espera para que las cookies se eliminen.
                usleep(2000);
            }
        }
        
        Messages::addSuccess(__('Cierre de sesión correcto.'), TRUE);
        $optionsManager = new OptionsManager();
        Util::redirect($optionsManager->getSiteUrl(), 'login');
    }
}
