<?php
/**
 * UsersManager.php
 */

namespace SoftnCMS\models\managers;

use SoftnCMS\models\CRUDManagerAbstract;
use SoftnCMS\models\tables\User;
use SoftnCMS\util\Arrays;
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
    
    const USER_PASSWORD   = 'user_password';
    
    const USER_ROL        = 'user_rol';
    
    const USER_REGISTERED = 'user_registered';
    
    const USER_URL        = 'user_url';
    
    /**
     * @param User $object
     */
    protected function addParameterQuery($object) {
        parent::parameterQuery(self::USER_EMAIL, $object->getUserEmail(), \PDO::PARAM_STR);
        parent::parameterQuery(self::USER_LOGIN, $object->getUserLogin(), \PDO::PARAM_STR);
        parent::parameterQuery(self::USER_NAME, $object->getUserName(), \PDO::PARAM_STR);
        parent::parameterQuery(self::USER_PASSWORD, $object->getUserPassword(), \PDO::PARAM_STR);
        parent::parameterQuery(self::USER_REGISTERED, $object->getUserRegistered(), \PDO::PARAM_STR);
        parent::parameterQuery(self::USER_ROL, $object->getUserRol(), \PDO::PARAM_INT);
        parent::parameterQuery(self::USER_URL, $object->getUserUrl(), \PDO::PARAM_STR);
    }
    
    protected function getTable() {
        return self::TABLE;
    }
    
    protected function buildObjectTable($result) {
        parent::buildObjectTable($result);
        $user = new User();
        $user->setId(Arrays::get($result, self::ID));
        $user->setUserUrl(Arrays::get($result, self::USER_URL));
        $user->setUserRol(Arrays::get($result, self::USER_ROL));
        $user->setUserRegistered(Arrays::get($result, self::USER_REGISTERED));
        $user->setUserName(Arrays::get($result, self::USER_NAME));
        $user->setUserLogin(Arrays::get($result, self::USER_LOGIN));
        $user->setUserEmail(Arrays::get($result, self::USER_EMAIL));
        
        return $user;
    }
    
}
