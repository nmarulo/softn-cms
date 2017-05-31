<?php
/**
 * UsersManager.php
 */

namespace SoftnCMS\models\managers;

use SoftnCMS\models\CRUDManagerAbstract;
use SoftnCMS\util\MySQL;

/**
 * Class UsersManager
 * @author Nicolás Marulanda P.
 */
class UsersManager extends CRUDManagerAbstract {
    
    const TABLE           = 'users';
    
    const USER_LOGIN      = 'user_login';
    
    const USER_NAME       = 'user_name';
    
    const USER_EMAIL      = 'user_email';
    
    const USER_PASS       = 'user_pass';
    
    const USER_ROL        = 'user_rol';
    
    const USER_REGISTERED = 'user_registered';
    
    const USER_URL        = 'user_url';
    
    protected function addParameterQuery($object) {
        // TODO: Implement addParameterQuery() method.
    }
    
    protected function getTable() {
        // TODO: Implement getTable() method.
    }
    
    protected function buildObjectTable($result, $fetch = MySQL::FETCH_ALL) {
        // TODO: Implement buildObjectTable() method.
    }
    
}
