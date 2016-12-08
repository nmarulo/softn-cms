<?php

/**
 * Modulo controlador: Pagina del formulario de registro de usuario.
 */

namespace SoftnCMS\controllers;

use SoftnCMS\helpers\form\builders\InputAlphanumericBuilder;
use SoftnCMS\helpers\form\builders\InputBooleanBuilder;
use SoftnCMS\helpers\form\builders\InputEmailBuilder;
use SoftnCMS\helpers\form\InputEmail;
use SoftnCMS\helpers\Helps;
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
            Form::setINPUT([
                InputAlphanumericBuilder::init('userLogin')
                                        ->setAccents(FALSE)
                                        ->setWithoutSpace(TRUE)
                                        ->setReplaceSpace('')
                                        ->build(),
                InputAlphanumericBuilder::init('userPass')
                                        ->build(),
                InputAlphanumericBuilder::init('userPassR')
                                        ->build(),
                InputEmailBuilder::init('userEmail')
                                 ->build(),
            ]);
            
            return Form::inputFilter();
        }
        
        return FALSE;
    }
    
}
