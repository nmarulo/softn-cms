<?php

/**
 * Modulo controlador: Pagina del cierre de sesión.
 */

namespace SoftnCMS\controllers;

use SoftnCMS\Helpers\Helps;
use SoftnCMS\models\Login;

/**
 * Clase LogoutController de la pagina del cierre de sesión.
 * @author Nicolás Marulanda P.
 */
class LogoutController extends Controller {
    
    /**
     * Método llamado por la función INDEX.
     *
     * @param int $paged Pagina actual
     *
     * @return array
     */
    protected function dataIndex($paged) {
        
        if (Login::isLogin()) {
            unset($_SESSION[SESSION_USER]);
            
            if (isset($_COOKIE[COOKIE_USER_REMEMBER])) {
                setcookie(COOKIE_USER_REMEMBER, '', time() - 10);
                //Tiempo de espera para que las cookies se eliminen.
                usleep(2000);
            }
            Messages::addSuccess('Cierre de sesión correcto.');
        }
        Helps::redirect(Router::getRoutes()['login']);
        
        return [];
    }
    
}
