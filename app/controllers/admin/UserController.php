<?php

/**
 * Modulo controlador: Pagina de usuarios del panel de administración.
 */
namespace SoftnCMS\controllers\admin;

use SoftnCMS\controllers\BaseController;
use SoftnCMS\controllers\Form;
use SoftnCMS\controllers\Messages;
use SoftnCMS\controllers\Pagination;
use SoftnCMS\controllers\Router;
use SoftnCMS\controllers\Token;
use SoftnCMS\helpers\ArrayHelp;
use SoftnCMS\helpers\form\builders\InputAlphabeticBuilder;
use SoftnCMS\helpers\form\builders\InputAlphanumericBuilder;
use SoftnCMS\helpers\form\builders\InputEmailBuilder;
use SoftnCMS\helpers\form\builders\InputIntegerBuilder;
use SoftnCMS\helpers\form\builders\InputUrlBuilder;
use SoftnCMS\helpers\Helps;
use SoftnCMS\models\admin\template\Template;
use SoftnCMS\models\admin\User;
use SoftnCMS\models\admin\Users;
use SoftnCMS\models\admin\UserInsert;
use SoftnCMS\models\admin\UserDelete;
use SoftnCMS\models\admin\UserUpdate;

/**
 * Clase UserController de la pagina de usuarios del panel de administración.
 * @author Nicolás Marulanda P.
 */
class UserController extends BaseController {
    
    /**
     * Método llamado por la función INDEX.
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
     * Método llamado por la función INSERT.
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
     * Método que obtiene los datos de los campos INPUT del formulario.
     * @return array|bool
     */
    protected function getDataInput() {
        if (Token::check()) {
            /*
             * Si "GetDataInput" es llamado desde la función "insert"
             * sera obligatorio los campos de las contraseñas.
             */
            $isRequire = Router::getRequest()
                               ->getMethod() == 'insert';
            
            Form::setINPUT([
                InputAlphanumericBuilder::init('userLogin')
                                        ->setAccents(FALSE)
                                        ->setWithoutSpace(TRUE)
                                        ->setReplaceSpace('')
                                        ->build(),
                InputAlphabeticBuilder::init('userName')
                                      ->build(),
                InputEmailBuilder::init('userEmail')
                                 ->build(),
                InputAlphanumericBuilder::init('userPass')
                                        ->setRequire($isRequire)
                                        ->setAccents(FALSE)
                                        ->build(),
                InputAlphanumericBuilder::init('userPassR')
                                        ->setRequire($isRequire)
                                        ->setAccents(FALSE)
                                        ->build(),
                InputIntegerBuilder::init('userRol')
                                   ->setRequire(FALSE)
                                   ->build(),
                InputUrlBuilder::init('userUrl')
                               ->setRequire(FALSE)
                               ->build(),
            ]);
            
            return Form::inputFilter();
        }
        
        return FALSE;
    }
    
    /**
     * Método llamado por la función UPDATE.
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
     * Método llamado por la función DELETE.
     *
     * @param array $data Lista de argumentos.
     */
    protected function dataDelete($data) {
        /*
         * Ya que este método no tiene modulo vista propio
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
