<?php

/**
 * Modulo del modelo de comentarios.
 * Gestiona el borrado de comentarios.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\controllers\DBController;
use SoftnCMS\models\admin\Comment;

/**
 * Clase que gestiona el borrado de comentarios.
 *
 * @author Nicolás Marulanda P.
 */
class CommentDelete {

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
     * Metodo que borra el comentarios de la base de datos.
     * @return bool Si es TRUE, todo se realizo correctamente.
     */
    public function delete() {
        $db = DBController::getConnection();
        $table = Comment::getTableName();
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
