<?php

/**
 * Modulo del modelo de etiquetas.
 * Gestiona el borrado de las etiquetas.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\controllers\DBController;
use SoftnCMS\models\admin\Category;

/**
 * Clase que gestiona el borrado de etiquetas.
 *
 * @author Nicolás Marulanda P.
 */
class TermDelete {

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
     * Metodo que borra la etiqueta de la base de datos.
     * @return bool Si es TRUE, todo se realizo correctamente.
     */
    public function delete() {
        $db = DBController::getConnection();
        $table = Term::getTableName();
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
