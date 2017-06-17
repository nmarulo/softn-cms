<?php
/**
 * UsersManager.php
 */

namespace SoftnCMS\models\managers;

use SoftnCMS\models\CRUDManagerAbstract;
use SoftnCMS\models\tables\Post;
use SoftnCMS\models\tables\User;
use SoftnCMS\util\Arrays;

/**
 * Class UsersManager
 * @author Nicolás Marulanda P.
 */
class UsersManager extends CRUDManagerAbstract {
    
    const TABLE                 = 'users';
    
    const USER_LOGIN            = 'user_login';
    
    const USER_NAME             = 'user_name';
    
    const USER_EMAIL            = 'user_email';
    
    const USER_PASSWORD         = 'user_password';
    
    const USER_PASSWORD_REWRITE = 'user_password_rewrite';
    
    const USER_ROL              = 'user_rol';
    
    const USER_REGISTERED       = 'user_registered';
    
    const USER_URL              = 'user_url';
    
    const USER_REMEMBER_ME      = 'user_remember_me';
    
    const USER_POST_COUNT       = 'user_post_count';
    
    /**
     * @param int $id
     *
     * @return bool
     */
    public function delete($id) {
        $user = $this->searchById($id);
        
        if ($user->getUserPostCount() == 0) {
            return parent::delete($id);
        }
        
        return FALSE;
    }
    
    /**
     * @param User $object
     *
     * @return bool
     */
    public function create($object) {
        if ($this->canCreate($object)) {
            return parent::create($object);
        }
        
        return FALSE;
    }
    
    /**
     * @param User $object
     *
     * @return bool
     */
    private function canCreate($object) {
        if ($this->searchByLogin($object) === FALSE && $this->searchByEmail($object) === FALSE) {
            return TRUE;
        }
        
        return FALSE;
    }
    
    /**
     * @param User $user
     *
     * @return bool|User
     */
    public function searchByLogin($user) {
        parent::parameterQuery(self::USER_LOGIN, $user->getUserLogin(), \PDO::PARAM_STR);
        
        return parent::searchBy(self::USER_LOGIN);
    }
    
    /**
     * @param User $user
     *
     * @return bool|User
     */
    public function searchByEmail($user) {
        parent::parameterQuery(self::USER_EMAIL, $user->getUserEmail(), \PDO::PARAM_STR);
        
        return parent::searchBy(self::USER_EMAIL);
    }
    
    /**
     * @param $userId
     * @param $num
     *
     * @return bool
     */
    public function updatePostCount($userId, $num) {
        $user = $this->searchById($userId);
        $user->setUserPostCount($user->getUserPostCount() + $num);
        
        return parent::update($user);
    }
    
    /**
     * @param int $postId
     *
     * @return User
     */
    public function searchByPostId($postId) {
        $columnPostId = PostsManager::ID;
        parent::parameterQuery($columnPostId, $postId, \PDO::PARAM_INT);
        $query = 'SELECT * ';
        $query .= 'FROM ' . parent::getTableWithPrefix();
        $query .= ' WHERE ' . self::ID;
        $query .= ' IN ';
        $query .= '(SELECT ' . PostsManager::USER_ID;
        $query .= ' FROM ' . parent::getTableWithPrefix(PostsManager::TABLE);
        $query .= " WHERE $columnPostId = :$columnPostId)";
        
        return Arrays::get(parent::readData($query), 0);
    }
    
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
        parent::parameterQuery(self::USER_POST_COUNT, $object->getUserPostCount(), \PDO::PARAM_INT);
    }
    
    protected function getTable() {
        return self::TABLE;
    }
    
    /**
     * @param $result
     *
     * @return User
     */
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
        $user->setUserPassword(Arrays::get($result, self::USER_PASSWORD));
        $user->setUserPostCount(Arrays::get($result, self::USER_POST_COUNT));
        
        return $user;
    }
    
}
