<?php
/**
 *
 */
/**
 * Modulo modelo: Gestiona los datos de un usuario para la plantilla de la aplicación.
 */
namespace SoftnCMS\models\theme;

use SoftnCMS\models\admin\User;

/**
 * Clase UserTemplate para gestionar los datos de un usuario para la plantilla de la aplicación.
 * @author Nicolás Marulanda P.
 */
class UserTemplate extends User {
    
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
