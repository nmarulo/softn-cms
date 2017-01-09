<?php

/**
 * Modulo modelo: Gestiona el borrado de usuarios.
 */
namespace SoftnCMS\models\admin;

use SoftnCMS\models\admin\base\ModelDelete;

/**
 * Clase UserDelete para gestionar el borrado de usuarios.
 * @author Nicolás Marulanda P.
 */
class UserDelete extends ModelDelete {
    
    /**
     * Constructor.
     *
     * @param int $id Identificador.
     */
    public function __construct($id) {
        parent::__construct($id, User::getTableName());
    }
    
}
