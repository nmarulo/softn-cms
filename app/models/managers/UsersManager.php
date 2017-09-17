<?php
/**
 * UsersManager.php
 */

namespace SoftnCMS\models\managers;

use SoftnCMS\models\CRUDManagerAbstract;
use SoftnCMS\models\tables\Comment;
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
        if ($this->searchByLogin($object->getUserLogin()) === FALSE && $this->searchByEmail($object->getUserEmail()) === FALSE) {
            return TRUE;
        }
        
        return FALSE;
    }
    
    /**
     * @param string $userLogin
     *
     * @return bool|User
     */
    public function searchByLogin($userLogin) {
        parent::parameterQuery(self::USER_LOGIN, $userLogin, \PDO::PARAM_STR);
        
        return parent::searchBy(self::USER_LOGIN);
    }
    
    /**
     * @param string $userEmail
     *
     * @return bool|User
     */
    public function searchByEmail($userEmail) {
        parent::parameterQuery(self::USER_EMAIL, $userEmail, \PDO::PARAM_STR);
        
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
        $tablePosts = parent::getTableWithPrefix(PostsManager::TABLE);
        $query      = 'SELECT * FROM %1$s WHERE %2$s IN (SELECT %3$s FROM %4$s WHERE %5$s = :%5$s)';
        $query      = sprintf($query, parent::getTableWithPrefix(), self::ID, PostsManager::USER_ID, $tablePosts, PostsManager::ID);
        
        return Arrays::get(parent::readData($query), 0);
    }
    
    /**
     * @param User $object
     *
     * @return bool
     */
    public function update($object) {
        $result = parent::update($object);
        
        if ($result) {
            $commentsManager = new CommentsManager();
            $comments        = $commentsManager->searchByUserId($object->getId());
            //TODO: Una mejor opción seria solo guardar los datos para usuario no registrados, y asi no tener que actualizar los datos de los usuarios registrados.
            array_walk($comments, function(Comment $comment) use ($commentsManager, $object) {
                $comment->setCommentAuthor($object->getUserName());
                $comment->setCommentAuthorEmail($object->getUserEmail());
                $commentsManager->update($comment);
            });
        }
        
        return $result;
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
        parent::parameterQuery(self::USER_URL, $object->getUserUrl(), \PDO::PARAM_STR);
        parent::parameterQuery(self::USER_POST_COUNT, $object->getUserPostCount(), \PDO::PARAM_INT);
        parent::parameterQuery(self::PROFILE_ID, $object->getProfileId(), \PDO::PARAM_INT);
        parent::parameterQuery(self::USER_URL_IMAGE, $object->getUserUrlImage(), \PDO::PARAM_STR);
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
