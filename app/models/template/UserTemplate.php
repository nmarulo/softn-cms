<?php
/**
 * UserTemplate.php
 */

namespace SoftnCMS\models\template;

use SoftnCMS\models\TemplateAbstract;
use SoftnCMS\models\managers\PostsManager;
use SoftnCMS\models\managers\UsersManager;
use SoftnCMS\models\tables\Post;
use SoftnCMS\models\tables\User;
use SoftnCMS\util\database\DBInterface;
use SoftnCMS\util\Logger;

/**
 * Class UserTemplate
 * @author Nicolás Marulanda P.
 */
class UserTemplate extends TemplateAbstract {
    
    /** @var User */
    private $user;
    
    /** @var array */
    private $post;
    
    /**
     * UserTemplate constructor.
     *
     * @param User        $user
     * @param bool        $initRelationship
     * @param string      $siteUrl
     * @param DBInterface $connectionDB
     */
    public function __construct(User $user = NULL, $initRelationship = FALSE, $siteUrl = '', DBInterface $connectionDB = NULL) {
        parent::__construct($siteUrl, $connectionDB);
        $this->user = $user;
        $this->post = [];
        
        if ($initRelationship) {
            $this->initRelationship();
        }
    }
    
    public function initRelationship() {
        $this->initPosts();
    }
    
    public function initPosts() {
        $postsManager = new PostsManager($this->getConnectionDB());
        $this->post   = $postsManager->searchAllByUserId($this->user->getId());
        $this->post   = array_map(function(Post $post) {
            return new PostTemplate($post, FALSE, $this->getSiteUrl(), $this->getConnectionDB());
        }, $this->post);
    }
    
    public function initUser($userId) {
        $usersManager = new UsersManager($this->getConnectionDB());
        $this->user   = $usersManager->searchById($userId);
        
        if (empty($this->user)) {
            Logger::getInstance()
                  ->error('El usuario no existe.', ['currentUserId' => $userId]);
            throw new \Exception("El usuario no existe.");
        }
    }
    
    /**
     * @return User
     */
    public function getUser() {
        return $this->user;
    }
    
    /**
     * @param User $user
     */
    public function setUser($user) {
        $this->user = $user;
    }
    
    /**
     * @return array
     */
    public function getPost() {
        return $this->post;
    }
    
}
