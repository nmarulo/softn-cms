<?php

/**
 * Modulo controlador: Pagina del formulario de inicio de sesión.
 */

namespace SoftnCMS\controllers;

use SoftnCMS\helpers\form\builders\InputAlphanumericBuilder;
use SoftnCMS\helpers\form\builders\InputBooleanBuilder;
use SoftnCMS\helpers\Helps;
use SoftnCMS\models\Login;

/**
 * Clase LoginController de la pagina del formulario de inicio de sesión.
 * @author Nicolás Marulanda P.
 */
class LoginController extends Controller {
    
    /**
     * Método llamado por la función INDEX.
     *
     * @param array $data Lista de argumentos.
     *
     * @return array
     */
    protected function dataIndex($data) {
        if (Form::submit('login')) {
            $dataInput = $this->getDataInput();
            
            if ($dataInput === FALSE) {
                Messages::addWarning('Completa todos los campos para continuar.');
            } else {
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
     * Método que obtiene los datos de los campos INPUT del formulario.
     * @return array|bool
     */
    private function getDataInput() {
        if (Token::check()) {
            Form::setINPUT([
                InputAlphanumericBuilder::init('userLogin')
                                        ->setAccents(FALSE)
                                        ->setWithoutSpace(TRUE)
                                        ->setReplaceSpace('')
                                        ->build(),
                InputAlphanumericBuilder::init('userPass')
                                        ->build(),
                InputBooleanBuilder::init('userRememberMe')
                                   ->build(),
            ]);
            
            return Form::inputFilter();
        }
        
        return FALSE;
    }
    
}
