<?php

/**
 * Modulo modelo: Gestiona los datos de una categoría para la plantilla de la aplicación.
 */
namespace SoftnCMS\models\theme;

use SoftnCMS\models\admin\Category;

/**
 * Clase CategoryTemplate para gestionar los datos de una categoría para la plantilla de la aplicación.
 * @author Nicolás Marulanda P.
 */
class CategoryTemplate extends Category {
    
    /**
     * Constructor.
     *
     * @param array $id
     */
    public function __construct($id) {
        $select = self::selectBy(self::getTableName(), $id, self::ID, \PDO::PARAM_INT);
        parent::__construct($select[0]);
    }
    
}
