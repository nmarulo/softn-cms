<?php

/**
 * Modulo del controlador del cierre de sesión.
 */

namespace SoftnCMS\controllers;

use SoftnCMS\controllers\Controller;
use SoftnCMS\models\Login;

/**
 * Clase controlador del cierre de sesión.
 *
 * @author Nicolás Marulanda P.
 */
class LogoutController extends Controller {

    /**
     * Metodo llamado por la función INDEX.
     * @return array
     */
    protected function dataIndex() {
        global $urlSite;
        
        if (Login::isLogin()) {
            unset($_SESSION['usernameID']);

            if (isset($_COOKIE['userRememberMe'])) {
                setcookie('userRememberMe', '', time() - 10);
                /** Tiempo de espera para que las cookies se eliminen. */
                usleep(2000);
            }
        }
        header("Location: $urlSite" . 'login');
        exit();
    }

}
