<?php

/**
 * Modulo del modelo post.
 * Gestiona el borrado de posts.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\models\admin\Post;
use SoftnCMS\controllers\DBController;

/**
 * Clase que gestiona el borrado de posts.
 *
 * @author Nicolás Marulanda P.
 */
class PostDelete {

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
     * Metodo que borra el post de la base de datos.
     * @return bool Si es TRUE, todo se realizo correctamente.
     */
    public function delete() {
        $db = DBController::getConnection();
        $table = Post::getTableName();
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
