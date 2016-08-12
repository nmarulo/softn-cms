<?php

/**
 * Modulo del controlador del formulario de inicio de sesión.
 */

namespace SoftnCMS\controllers;

use SoftnCMS\controllers\Controller;
use SoftnCMS\models\Login;

/**
 * Clase controlador del inicio de sesión.
 *
 * @author Nicolás Marulanda P.
 */
class LoginController extends Controller {

    /**
     * Metodo llamado por la función INDEX.
     * @return array
     */
    protected function dataIndex() {
        if (\filter_input(\INPUT_POST, 'login')) {
            $dataInput = $this->getDataInput();

            if ($dataInput['userLogin'] && $dataInput['userPass']) {
                $login = new Login($dataInput['userLogin'], $dataInput['userPass'], $dataInput['userRememberMe']);
                $login->login();
            }
        }
        
        return [];
    }

    /**
     * Metodo que obtiene los datos de los campos INPUT del formulario.
     * @return array
     */
    private function getDataInput() {
        return [
            'userLogin' => \filter_input(\INPUT_POST, 'userLogin'),
            'userPass' => \filter_input(\INPUT_POST, 'userPass'),
            'userRememberMe' => \filter_input(\INPUT_POST, 'userRememberMe'),
        ];
    }

}
