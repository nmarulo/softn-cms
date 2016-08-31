<?php

/**
 * Modulo del controlador del formulario de registro de usuario.
 */

namespace SoftnCMS\controllers;

use SoftnCMS\controllers\Controller;
use SoftnCMS\controllers\Messages;
use SoftnCMS\models\Register;

/**
 * Clase controlador de registro de usuarios.
 *
 * @author Nicolás Marulanda P.
 */
class RegisterController extends Controller {

    /**
     * Metodo llamado por la función INDEX.
     * @return array
     */
    protected function dataIndex() {
        global $urlSite;

        if (\filter_input(\INPUT_POST, 'register')) {
            $dataInput = $this->getDataInput();

            if ($this->checkPasswords($dataInput)) {
                $register = new Register($dataInput['userLogin'], $dataInput['userEmail'], $dataInput['userPass']);
                
                if ($register->register()) {
                    Messages::addSuccess('Usuario registrado correctamente.');
                    \header("Location: $urlSite" . 'login');
                    exit();
                }
            }
            Messages::addError('Error al registrar el usuario.');
        }

        return [];
    }

    /**
     * Metodo que comprueba los datos de las contraseñas.
     * @param arrat $dataInput
     * @return bool
     */
    private function checkPasswords($dataInput) {
        if ($dataInput['userPass'] && $dataInput['userPassR'] && $dataInput['userPass'] == $dataInput['userPassR']) {
            return \TRUE;
        }

        return \FALSE;
    }

    /**
     * Metodo que obtiene los datos de los campos INPUT del formulario.
     * @return array
     */
    private function getDataInput() {
        return [
            'userLogin' => \filter_input(\INPUT_POST, 'userLogin'),
            'userPass' => \filter_input(\INPUT_POST, 'userPass'),
            'userPassR' => \filter_input(\INPUT_POST, 'userPassR'),
            'userEmail' => \filter_input(\INPUT_POST, 'userEmail'),
        ];
    }

}
