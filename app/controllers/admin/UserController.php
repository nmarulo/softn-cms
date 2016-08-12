<?php

/**
 * Modulo del controlador de la pagina de usuarios.
 */

namespace SoftnCMS\controllers\admin;

use SoftnCMS\controllers\BaseController;
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
     * @return array
     */
    protected function dataIndex() {
        $users = Users::selectAll();
        $output = $users->getUsers();

        return ['users' => $output];
    }

    /**
     * Metodo llamado por la función UPDATE.
     * @param int $id
     * @return array
     */
    protected function dataUpdate($id) {
        global $urlSite;

        $user = User::selectByID($id);

        //En caso de que no exista.
        if (empty($user)) {
            header("Location: $urlSite" . 'admin/user');
            exit();
        }

        if (filter_input(\INPUT_POST, 'update')) {
            $dataInput = $this->getDataInput();

            if ($dataInput['userPass'] == $dataInput['userPassR']) {
                $update = new UserUpdate($user, $dataInput['userLogin'], $dataInput['userName'], $dataInput['userEmail'], $dataInput['userPass'], $dataInput['userRol'], $dataInput['userUrl']);

                //Si ocurre un error la función "$update->update()" retorna FALSE.
                if ($update->update()) {
                    $user = $update->getUser();
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
     * @param int $id
     * @return array
     */
    protected function dataDelete($id) {
        /*
         * Ya que este metodo no tiene modulo vista propio
         * se carga el modulo vista INDEX, asi que se retornan los datos
         * para esta vista.
         */

        $delete = new UserDelete($id);
        $delete->delete();

        return $this->dataIndex();
    }

    /**
     * Metodo llamado por la función INSERT.
     * @return array
     */
    protected function dataInsert() {
        if (filter_input(\INPUT_POST, 'publish')) {
            global $urlSite;

            $dataInput = $this->getDataInput();
            if ($dataInput['userPass'] == $dataInput['userPassR']) {
                $insert = new UserInsert($dataInput['userLogin'], $dataInput['userName'], $dataInput['userEmail'], $dataInput['userPass'], $dataInput['userRol'], $dataInput['userUrl']);

                if ($insert->insert()) {
                    //Si todo es correcto se muestra el USER en la pagina de edición.
                    header("Location: $urlSite" . 'admin/user/update/' . $insert->getLastInsertId());
                    exit();
                }
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
