<?php

/**
 * Modulo del controlador del formulario de inicio de sesión.
 */

namespace SoftnCMS\controllers;

use SoftnCMS\Helpers\Helps;
use SoftnCMS\models\Login;

/**
 * Clase controlador del inicio de sesión.
 * @author Nicolás Marulanda P.
 */
class LoginController extends Controller {
    
    /**
     * Metodo llamado por la función INDEX.
     *
     * @param int $paged Pagina actual
     *
     * @return array
     */
    protected function dataIndex($paged) {
        if (Form::submit('login')) {
            $dataInput = $this->getDataInput();
            
            if($dataInput === FALSE){
                Messages::addWarning('Completa todos los campos para continuar.');
            }else {
                if ($dataInput['userLogin'] && $dataInput['userPass']) {
                    $login = new Login($dataInput['userLogin'], $dataInput['userPass'], $dataInput['userRememberMe']);
        
                    if ($login->login()) {
                        Messages::addSuccess('Inicio de sesión correcto.');
                        Helps::redirect(Router::getRoutes()['admin']);
                    } else {
                        Messages::addError('Error. El usuario o la contraseña es incorrecta.');
                    }
                } else {
                    Messages::addWarning('Completa todos los campos para continuar.');
                }
            }
        }
        
        return [];
    }
    
    /**
     * Metodo que obtiene los datos de los campos INPUT del formulario.
     * @return array|bool
     */
    private function getDataInput() {
        if(Token::check()) {
            Form::addInputAlphanumeric('userLogin', TRUE, FALSE, FALSE, FALSE, 1, TRUE, '');
            Form::addInputAlphanumeric('userPass', TRUE);
            Form::addInputBoolean('userRememberMe');
    
            return Form::postInput();
        }
        
        return FALSE;
    }
    
}
