<?php

/**
 * Modulo controlador: Pagina del formulario de registro de usuario.
 */

namespace SoftnCMS\controllers;

use SoftnCMS\Helpers\Helps;
use SoftnCMS\models\Register;

/**
 * Clase RegisterController de la pagina del formulario de registro de usuario.
 * @author Nicolás Marulanda P.
 */
class RegisterController extends Controller {
    
    /**
     * Método llamado por la función INDEX.
     *
     * @param array $data Lista de argumentos.
     *
     * @return array
     */
    protected function dataIndex($data) {
        if (Form::submit('register')) {
            $dataInput = $this->getDataInput();
            
            if ($dataInput === FALSE) {
                Messages::addError('Error al registrar el usuario.');
            } else {
                if ($dataInput['userPass'] == $dataInput['userPassR']) {
                    $register = new Register($dataInput['userLogin'], $dataInput['userEmail'], $dataInput['userPass']);
                    
                    if ($register->register()) {
                        Messages::addSuccess('Usuario registrado correctamente.');
                        Helps::redirect(Router::getRoutes()['login']);
                    }
                }
                Messages::addError('Error al registrar el usuario.');
            }
        }
        
        return [];
    }
    
    /**
     * Método que obtiene los datos de los campos INPUT del formulario.
     * @return bool|array
     */
    private function getDataInput() {
        if (Token::check()) {
            Form::addInputAlphanumeric('userLogin', TRUE, FALSE, FALSE);
            Form::addInputAlphanumeric('userPass', TRUE, FALSE, FALSE);
            Form::addInputAlphanumeric('userPassR', TRUE, FALSE, FALSE);
            Form::addInputEmail('userEmail', TRUE);
            
            return Form::postInput();
        }
        
        return FALSE;
    }
    
}
