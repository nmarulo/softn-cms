<?php

/**
 * Modulo del modelo usuario.
 * Gestiona el borrado de usuarios.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\models\admin\User;
use SoftnCMS\controllers\DBController;

/**
 * Clase que gestiona el borrado de usuarios.
 *
 * @author Nicolás Marulanda P.
 */
class UserDelete {

    /** @var int Identificador. */
    private $id;

    /**
     * Constructor.
     * @param int $id Identificador.
     */
    public function __construct($id) {
        $this->id = $id;
    }

    /**
     * Metodo que borra el usuario de la base de datos.
     * @return bool Si es TRUE, todo se realizo correctamente.
     */
    public function delete() {
        $db = DBController::getConnection();
        $table = User::getTableName();
        $parameter = ':id';
        $where = "ID = $parameter";
        $newData = $this->id;
        $dataType = \PDO::PARAM_INT;
        $prepare = [
            DBController::prepareStatement($parameter, $newData, $dataType)
        ];
        
        return $db->delete($table, $where, $prepare);
    }

}
