<?php

/**
 * Modulo del controlador del cierre de sesión.
 */

namespace SoftnCMS\controllers;

use SoftnCMS\models\Login;

/**
 * Clase controlador del cierre de sesión.
 *
 * @author Nicolás Marulanda P.
 */
class LogoutController extends Controller {

    /**
     * Metodo llamado por la función INDEX.
     * @param int $paged Pagina actual
     * @return array
     */
    protected function dataIndex($paged) {
        global $urlSite;

        if (Login::isLogin()) {
            unset($_SESSION[SESSION_USER]);

            if (isset($_COOKIE[COOKIE_USER_REMEMBER])) {
                setcookie(COOKIE_USER_REMEMBER, '', time() - 10);
                /** Tiempo de espera para que las cookies se eliminen. */
                usleep(2000);
            }
            Messages::addSuccess('Cierre de sesión correcto.');
        }
        header("Location: $urlSite" . 'login');
        exit();
    }

}
