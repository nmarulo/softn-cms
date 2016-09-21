<?php

/**
 * Modulo del controlador de la pagina de usuarios.
 */

namespace SoftnCMS\controllers\admin;

use SoftnCMS\controllers\BaseController;
use SoftnCMS\controllers\Messages;
use SoftnCMS\controllers\Pagination;
use SoftnCMS\models\admin\User;
use SoftnCMS\models\admin\Users;
use SoftnCMS\models\admin\UserInsert;
use SoftnCMS\models\admin\UserDelete;
use SoftnCMS\models\admin\UserUpdate;

/**
 * Clase del controlador de la pagina de usuarios.
 *
 * @author Nicolás Marulanda P.
 */
class UserController extends BaseController {

    /**
     * Metodo llamado por la funcion INDEX.
     * @param array $data Lista de argumentos..
     * @return array
     */
    protected function dataIndex($data) {
        $output = [];
        $countData = Users::count();
        $pagination = new Pagination($data['paged'], $countData);
        $limit = $pagination->getBeginRow() . ',' . $pagination->getRowCount();
        $users = Users::selectByLimit($limit);

        $pagination->concatUrl('admin/user');

        if ($users !== \FALSE) {
            $output = $users->getAll();
        }

        return [
            'users' => $output,
            'pagination' => $pagination
        ];
    }

    /**
     * Metodo llamado por la función INSERT.
     * @return array
     */
    protected function dataInsert() {
        global $urlSite;

        if (filter_input(\INPUT_POST, 'publish')) {

            $dataInput = $this->getDataInput();
            if ($dataInput['userPass'] == $dataInput['userPassR']) {
                $insert = new UserInsert($dataInput['userLogin'], $dataInput['userName'], $dataInput['userEmail'], $dataInput['userPass'], $dataInput['userRol'], $dataInput['userUrl']);

                if ($insert->insert()) {
                    Messages::addSuccess('Usuario registrado correctamente.');
                    //Si todo es correcto se muestra el USER en la pagina de edición.
                    header("Location: $urlSite" . 'admin/user/update/' . $insert->getLastInsertId());
                    exit();
                }

                Messages::addError('Error al registrar el usuario.');
            }
        }

        return [
            //Datos por defecto a mostrar en el formulario.
            'user' => User::defaultInstance(),
            /*
             * Booleano que indica si muestra el encabezado
             * "Agregar nuevo usuario" si es FALSE 
             * o "Actualizar usuario" si es TRUE
             */
            'actionUpdate' => \FALSE,
        ];
    }

    /**
     * Metodo llamado por la función UPDATE.
     * @param array $data Lista de argumentos.
     * @return array
     */
    protected function dataUpdate($data) {
        global $urlSite;

        $user = User::selectByID($data['id']);

        //En caso de que no exista.
        if (empty($user)) {
            Messages::addError('Error. El usuario no existe.');
            header("Location: $urlSite" . 'admin/user');
            exit();
        }

        if (filter_input(\INPUT_POST, 'update')) {
            $dataInput = $this->getDataInput();

            if ($dataInput['userPass'] == $dataInput['userPassR']) {
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
            /*
             * Booleano que indica si muestra el encabezado
             * "Agregar nuevo usuario" si es FALSE 
             * o "Actualizar usuario" si es TRUE
             */
            'actionUpdate' => \TRUE,
        ];
    }

    /**
     * Metodo llamado por la función DELETE.
     * @param array $data Lista de argumentos.
     * @return array
     */
    protected function dataDelete($data) {
        /*
         * Ya que este metodo no tiene modulo vista propio
         * se carga el modulo vista INDEX, asi que se retornan los datos
         * para esta vista.
         */

        $delete = new UserDelete($data['id']);
        $output = $delete->delete();

        if ($output) {
            Messages::addSuccess('Usuario borrado correctamente.');
        } elseif ($output === 0) {
            Messages::addWarning('El usuario no existe.');
        } else {
            Messages::addError('Error al borrar el usuario.');
        }

        $this->namePage = 'user';
    }

    /**
     * Metodo que obtiene los datos de los campos INPUT del formulario.
     * @return array
     */
    protected function getDataInput() {
        return [
            'userLogin' => \filter_input(\INPUT_POST, 'userLogin'),
            'userName' => \filter_input(\INPUT_POST, 'userName'),
            'userEmail' => \filter_input(\INPUT_POST, 'userEmail'),
            'userPass' => \filter_input(\INPUT_POST, 'userPass'),
            'userPassR' => \filter_input(\INPUT_POST, 'userPassR'),
            'userRol' => \filter_input(\INPUT_POST, 'userRol'),
            'userUrl' => \filter_input(\INPUT_POST, 'userUrl'),
        ];
    }

}
