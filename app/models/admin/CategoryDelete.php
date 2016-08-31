<?php

/**
 * Modulo del modelo de categorías.
 * Gestiona el borrado de las categorías.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\models\admin\Category;
use SoftnCMS\models\admin\base\ModelDelete;

/**
 * Clase que gestiona el borrado de categorías.
 *
 * @author Nicolás Marulanda P.
 */
class CategoryDelete extends ModelDelete{
    
    /**
     * Constructor.
     * @param int $id Identificador.
     */
    public function __construct($id) {
        parent::__construct($id, Category::getTableName());
    }

}
