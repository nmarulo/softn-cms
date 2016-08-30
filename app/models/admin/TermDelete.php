<?php

/**
 * Modulo del modelo de etiquetas.
 * Gestiona el borrado de las etiquetas.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\models\admin\Term;
use SoftnCMS\models\admin\base\ModelDelete;

/**
 * Clase que gestiona el borrado de etiquetas.
 *
 * @author Nicolás Marulanda P.
 */
class TermDelete extends ModelDelete {

    /**
     * Constructor.
     * @param int $id Identificador.
     */
    public function __construct($id) {
        parent::__construct($id, Term::getTableName());
    }

}
