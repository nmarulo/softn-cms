<?php

/**
 * Modulo modelo: Gestiona el borrado de posts.
 */
namespace SoftnCMS\models\admin;

use SoftnCMS\models\admin\base\ModelDelete;

/**
 * Clase PostDelete para gestionar el borrado de posts.
 * @author Nicolás Marulanda P.
 */
class PostDelete extends ModelDelete {
    
    /**
     * Constructor.
     *
     * @param int $id Identificador.
     */
    public function __construct($id) {
        parent::__construct($id, Post::getTableName());
    }
    
}
