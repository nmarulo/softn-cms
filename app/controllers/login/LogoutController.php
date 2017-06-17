<?php
/**
 * LogoutController.php
 */

namespace SoftnCMS\controllers\login;

use SoftnCMS\controllers\ControllerAbstract;
use SoftnCMS\models\managers\LoginManager;
use SoftnCMS\models\managers\OptionsManager;
use SoftnCMS\util\Messages;
use SoftnCMS\util\Util;

/**
 * Class LogoutController
 * @author Nicolás Marulanda P.
 */
class LogoutController extends ControllerAbstract {
    
    public function index() {
        $this->read();
        Messages::addSessionMessage('Cierre de sesión correcto.', Messages::TYPE_SUCCESS);
        $optionsManager = new OptionsManager();
        Util::redirect($optionsManager->getSiteUrl(), 'login');
    }
    
    protected function read() {
        if (LoginManager::isLogin()) {
            unset($_SESSION[SESSION_USER]);
            
            if (isset($_COOKIE[COOKIE_USER_REMEMBER])) {
                setcookie(COOKIE_USER_REMEMBER, '', time() - 10);
                //Tiempo de espera para que las cookies se eliminen.
                usleep(2000);
            }
        }
    }
    
}
