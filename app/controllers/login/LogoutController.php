<?php
/**
 * LogoutController.php
 */

namespace SoftnCMS\controllers\login;

use SoftnCMS\models\managers\LoginManager;
use SoftnCMS\util\controller\ControllerAbstract;
use SoftnCMS\util\Messages;

/**
 * Class LogoutController
 * @author Nicolás Marulanda P.
 */
class LogoutController extends ControllerAbstract {
    
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
        $this->redirect('login');
    }
}
