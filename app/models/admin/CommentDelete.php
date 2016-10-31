<?php

/**
 * Modulo modelo: Gestiona el borrado de comentarios.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\models\admin\base\ModelDelete;

/**
 * Clase que gestiona el borrado de comentarios.
 * @author Nicolás Marulanda P.
 */
class CommentDelete extends ModelDelete {
    
    /**
     * Constructor.
     *
     * @param int $id Identificador.
     */
    public function __construct($id) {
        parent::__construct($id, Comment::getTableName());
    }
    
}
