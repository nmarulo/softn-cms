<?php

/**
 * Modulo del controlador de la pagina de usuarios.
 */

namespace SoftnCMS\controllers\admin;

use SoftnCMS\controllers\BaseController;
use SoftnCMS\controllers\Form;
use SoftnCMS\controllers\Messages;
use SoftnCMS\controllers\Pagination;
use SoftnCMS\controllers\Router;
use SoftnCMS\controllers\Token;
use SoftnCMS\Helpers\ArrayHelp;
use SoftnCMS\Helpers\Helps;
use SoftnCMS\models\admin\template\Template;
use SoftnCMS\models\admin\User;
use SoftnCMS\models\admin\Users;
use SoftnCMS\models\admin\UserInsert;
use SoftnCMS\models\admin\UserDelete;
use SoftnCMS\models\admin\UserUpdate;

/**
 * Clase del controlador de la pagina de usuarios.
 * @author Nicolás Marulanda P.
 */
class UserController extends BaseController {
    
    /**
     * Metodo llamado por la funcion INDEX.
     *
     * @param array $data Lista de argumentos.
     *
     * @return array
     */
    protected function dataIndex($data) {
        $output     = [];
        $countData  = Users::count();
        $pagination = new Pagination(ArrayHelp::get($data, 'paged'), $countData);
        $limit      = $pagination->getBeginRow() . ',' . $pagination->getRowCount();
        $users      = Users::selectByLimit($limit);
        Template::setPagination($pagination);
        
        if ($users !== \FALSE) {
            $output = $users->getAll();
        }
        
        return [
            'users' => $output,
        ];
    }
    
    /**
     * Metodo llamado por la función INSERT.
     * @return array
     */
    protected function dataInsert() {
        if (Form::submit('publish')) {
            $dataInput = $this->getDataInput();
            
            if ($dataInput !== FALSE && $dataInput['userPass'] == $dataInput['userPassR']) {
                $insert = new UserInsert($dataInput['userLogin'], $dataInput['userName'], $dataInput['userEmail'], $dataInput['userPass'], $dataInput['userRol'], $dataInput['userUrl']);
                
                if ($insert->insert()) {
                    Messages::addSuccess('Usuario registrado correctamente.');
                    //Si es correcto se muestra el USER en la pagina de edición.
                    Helps::redirectRoute('update/' . $insert->getLastInsertId());
                }
            }
            Messages::addError('Error al registrar el usuario.');
        }
        
        return [
            //Datos por defecto a mostrar en el formulario.
            'user' => User::defaultInstance(),
        ];
    }
    
    /**
     * Metodo que obtiene los datos de los campos INPUT del formulario.
     * @return array|bool
     */
    protected function getDataInput() {
        if (Token::check()) {
            /*
             * Si "GetDataInput" es llamado desde la función "insert"
             * sera obligatorio los campos de las contraseñas.
             */
            $isInsert = Router::getRequest()->getMethod() == 'insert';
            
            Form::addInputAlphanumeric('userLogin', TRUE, FALSE, FALSE, FALSE, 1, TRUE, '');
            Form::addInputAlphabetic('userName', TRUE);
            Form::addInputEmail('userEmail', TRUE);
            Form::addInputAlphanumeric('userPass', $isInsert);
            Form::addInputAlphanumeric('userPassR', $isInsert);
            Form::addInputInteger('userRol');
            Form::addInputUrl('userUrl');
            
            return Form::postInput();
        }
        
        return FALSE;
    }
    
    /**
     * Metodo llamado por la función UPDATE.
     *
     * @param array $data Lista de argumentos.
     *
     * @return array
     */
    protected function dataUpdate($data) {
        $user = User::selectByID(ArrayHelp::get($data, 'id'));
        
        //En caso de que no exista.
        if (empty($user)) {
            Messages::addError('Error. El usuario no existe.');
            Helps::redirectRoute();
        }
        
        if (Form::submit('update')) {
            $dataInput = $this->getDataInput();
            
            if ($dataInput === FALSE || $dataInput['userPass'] != $dataInput['userPassR']) {
                Messages::addError('Error al actualizar el usuario.');
            } else {
                $update = new UserUpdate($user, $dataInput['userLogin'], $dataInput['userName'], $dataInput['userEmail'], $dataInput['userPass'], $dataInput['userRol'], $dataInput['userUrl']);
                
                //Si ocurre un error la función "$update->update()" retorna FALSE.
                if ($update->update()) {
                    Messages::addSuccess('Usuario actualizado correctamente.');
                    $user = $update->getDataUpdate();
                } else {
                    Messages::addError('Error al actualizar el usuario.');
                }
            }
        }
        
        return [
            //Instancia USER
            'user' => $user,
        ];
    }
    
    /**
     * Metodo llamado por la función DELETE.
     *
     * @param array $data Lista de argumentos.
     */
    protected function dataDelete($data) {
        /*
         * Ya que este metodo no tiene modulo vista propio
         * se carga el modulo vista INDEX, asi que se retornan los datos
         * para esta vista.
         */
        
        $output = FALSE;
        
        if (Token::check()) {
            $delete = new UserDelete($data['id']);
            $output = $delete->delete();
        }
        
        if ($output) {
            Messages::addSuccess('Usuario borrado correctamente.');
        } elseif ($output === 0) {
            Messages::addWarning('El usuario no existe.');
        } else {
            Messages::addError('Error al borrar el usuario.');
        }
    }
    
}
