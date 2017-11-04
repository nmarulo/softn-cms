<?php
/**
 * UsersManager.php
 */

namespace SoftnCMS\models\managers;

use SoftnCMS\models\tables\Comment;
use SoftnCMS\models\tables\User;
use SoftnCMS\util\Arrays;
use SoftnCMS\util\database\ManagerAbstract;

/**
 * Class UsersManager
 * @author Nicolás Marulanda P.
 */
class UsersManager extends ManagerAbstract {
    
    const TABLE                 = 'users';
    
    const USER_LOGIN            = 'user_login';
    
    const USER_NAME             = 'user_name';
    
    const USER_EMAIL            = 'user_email';
    
    const USER_PASSWORD         = 'user_password';
    
    const USER_PASSWORD_REWRITE = 'user_password_rewrite';
    
    const USER_REGISTERED       = 'user_registered';
    
    const USER_URL              = 'user_url';
    
    const USER_REMEMBER_ME      = 'user_remember_me';
    
    const USER_POST_COUNT       = 'user_post_count';
    
    const PROFILE_ID            = 'profile_id';
    
    const USER_URL_IMAGE        = 'user_url_image';
    
    /**
     * @param int $id
     *
     * @return bool
     */
    public function deleteById($id) {
        $user = $this->searchById($id);
        
        if ($user->getUserPostCount() == 0) {
            return parent::deleteById($id);
        }
        
        return FALSE;
    }
    
    /**
     * @param User $object
     *
     * @return bool
     */
    public function create($object) {
        if ($this->checkLoginAndEmail($object)) {
            return parent::create($object);
        }
        
        return FALSE;
    }
    
    /**
     * @param User $object
     *
     * @return bool
     */
    private function checkLoginAndEmail($object) {
        $result = $this->searchByLoginAndEmail($object->getUserLogin(), $object->getUserEmail(), $object->getId());
        
        //Si no esta vació, existe otro usuario con el mismo login o email.
        return empty($result);
    }
    
    /**
     * @param string $login
     * @param string $email
     * @param int    $id
     *
     * @return bool|array
     */
    private function searchByLoginAndEmail($login, $email, $id = NULL) {
        $whereId = '';
        
        if (!empty($id)) {
            parent::addPrepareStatement(self::COLUMN_ID, $id, \PDO::PARAM_INT);
            $whereId = sprintf('%1$s != :%1$s AND ', self::COLUMN_ID);
        }
        
        parent::addPrepareStatement(self::USER_LOGIN, $login, \PDO::PARAM_STR);
        parent::addPrepareStatement(self::USER_EMAIL, $email, \PDO::PARAM_STR);
        $query = 'SELECT * FROM %1$s WHERE %4$s(%2$s = :%2$s OR %3$s = :%3$s)';
        $query = sprintf($query, parent::getTableWithPrefix(), self::USER_LOGIN, self::USER_EMAIL, $whereId);
        
        return parent::search($query);
    }
    
    /**
     * @param string $userLogin
     *
     * @return bool|User
     */
    public function searchByLogin($userLogin) {
        $result = parent::searchAllByColumn($userLogin, self::USER_LOGIN, \PDO::PARAM_STR);
        
        return Arrays::findFirst($result);
    }
    
    public function searchByLoginAndPassword($userLogin, $userPassword) {
        parent::addPrepareStatement(self::USER_LOGIN, $userLogin, \PDO::PARAM_STR);
        parent::addPrepareStatement(self::USER_PASSWORD, $userPassword, \PDO::PARAM_STR);
        $query = sprintf('SELECT * FROM %1$s WHERE %2$s = :%2$s AND %3$s = :%3$s', parent::getTableWithPrefix(), self::USER_LOGIN, self::USER_PASSWORD);
        
        return Arrays::findFirst(parent::search($query));
    }
    
    /**
     * @param string $userEmail
     *
     * @return bool|User
     */
    public function searchByEmail($userEmail) {
        $result = parent::searchAllByColumn($userEmail, self::USER_EMAIL, \PDO::PARAM_STR);
        
        return Arrays::findFirst($result);
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
        
        return parent::updateByColumnId($user);
    }
    
    /**
     * @param int $postId
     *
     * @return User
     */
    public function searchByPostId($postId) {
        $columnPostId = PostsManager::COLUMN_ID;
        parent::addPrepareStatement($columnPostId, $postId, \PDO::PARAM_INT);
        $tablePosts = parent::getTableWithPrefix(PostsManager::TABLE);
        $query      = 'SELECT * FROM %1$s WHERE %2$s IN (SELECT %3$s FROM %4$s WHERE %5$s = :%5$s)';
        $query      = sprintf($query, parent::getTableWithPrefix(), self::COLUMN_ID, PostsManager::USER_ID, $tablePosts, PostsManager::COLUMN_ID);
        
        return Arrays::findFirst(parent::search($query));
    }
    
    /**
     * @param User $object
     *
     * @return bool
     */
    public function updateByColumnId($object) {
        if (!$this->checkLoginAndEmail($object)) {
            return FALSE;
        }
        
        $result = parent::updateByColumnId($object);
        
        if ($result) {
            $commentsManager = new CommentsManager();
            $comments        = $commentsManager->searchByUserId($object->getId());
            //TODO: Una mejor opción seria solo guardar los datos para usuario no registrados, y asi no tener que actualizar los datos de los usuarios registrados.
            array_walk($comments, function(Comment $comment) use ($commentsManager, $object) {
                $comment->setCommentAuthor($object->getUserName());
                $comment->setCommentAuthorEmail($object->getUserEmail());
                $commentsManager->updateByColumnId($comment);
            });
        }
        
        return $result;
    }
    
    /**
     * @param User $object
     */
    protected function prepareStatement($object) {
        parent::addPrepareStatement(self::COLUMN_ID, $object->getId(), \PDO::PARAM_INT);
        parent::addPrepareStatement(self::USER_EMAIL, $object->getUserEmail(), \PDO::PARAM_STR);
        parent::addPrepareStatement(self::USER_LOGIN, $object->getUserLogin(), \PDO::PARAM_STR);
        parent::addPrepareStatement(self::USER_NAME, $object->getUserName(), \PDO::PARAM_STR);
        parent::addPrepareStatement(self::USER_PASSWORD, $object->getUserPassword(), \PDO::PARAM_STR);
        parent::addPrepareStatement(self::USER_REGISTERED, $object->getUserRegistered(), \PDO::PARAM_STR);
        parent::addPrepareStatement(self::USER_URL, $object->getUserUrl(), \PDO::PARAM_STR);
        parent::addPrepareStatement(self::USER_POST_COUNT, $object->getUserPostCount(), \PDO::PARAM_INT);
        parent::addPrepareStatement(self::PROFILE_ID, $object->getProfileId(), \PDO::PARAM_INT);
        parent::addPrepareStatement(self::USER_URL_IMAGE, $object->getUserUrlImage(), \PDO::PARAM_STR);
    }
    
    protected function getTable() {
        return self::TABLE;
    }
    
    /**
     * @param $result
     *
     * @return User
     */
    protected function buildObject($result) {
        $user = new User();
        $user->setId(Arrays::get($result, self::COLUMN_ID));
        $user->setUserUrl(Arrays::get($result, self::USER_URL));
        $user->setUserRegistered(Arrays::get($result, self::USER_REGISTERED));
        $user->setUserName(Arrays::get($result, self::USER_NAME));
        $user->setUserLogin(Arrays::get($result, self::USER_LOGIN));
        $user->setUserEmail(Arrays::get($result, self::USER_EMAIL));
        $user->setUserPassword(Arrays::get($result, self::USER_PASSWORD));
        $user->setUserPostCount(Arrays::get($result, self::USER_POST_COUNT));
        $user->setProfileId(Arrays::get($result, self::PROFILE_ID));
        $user->setUserUrlImage(Arrays::get($result, self::USER_URL_IMAGE));
        
        return $user;
    }
    
}
