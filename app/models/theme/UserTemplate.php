<?php
/**
 *
 */

namespace SoftnCMS\models\theme;

use SoftnCMS\models\admin\User;

/**
 * Class UserTemplate
 * @author Nicolás Marulanda P.
 */
class UserTemplate extends User{
    
    public function __construct($id) {
        $select = self::selectBy(self::getTableName(), $id, self::ID, \PDO::PARAM_INT);
        parent::__construct($select[0]);
    }
    
}
