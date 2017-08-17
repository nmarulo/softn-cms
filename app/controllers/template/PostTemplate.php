<?php
/**
 * PostTemplate.php
 */

namespace SoftnCMS\controllers\template;

use SoftnCMS\controllers\Template;
use SoftnCMS\models\managers\CategoriesManager;
use SoftnCMS\models\managers\CommentsManager;
use SoftnCMS\models\managers\PostsManager;
use SoftnCMS\models\managers\TermsManager;
use SoftnCMS\models\managers\UsersManager;
use SoftnCMS\models\tables\Category;
use SoftnCMS\models\tables\Comment;
use SoftnCMS\models\tables\Post;
use SoftnCMS\models\tables\Term;

/**
 * Class PostTemplate
 * @author Nicolás Marulanda P.
 */
class PostTemplate extends Template {
    
    /** @var Post */
    private $post;
    
    /** @var array */
    private $categoriesTemplate;
    
    /** @var array */
    private $termsTemplate;
    
    /** @var UserTemplate */
    private $userTemplate;
    
    /** @var array */
    private $commentsTemplate;
    
    /**
     * PostTemplate constructor.
     *
     * @param Post $post
     * @param bool $initRelationship
     */
    public function __construct(Post $post = NULL, $initRelationship = FALSE) {
        parent::__construct();
        $this->post               = $post;
        $this->categoriesTemplate = [];
        $this->termsTemplate      = [];
        $this->userTemplate       = NULL;
        
        if ($initRelationship) {
            $this->initRelationship();
        }
    }
    
    public function initRelationship() {
        $this->initUser();
        $this->initCategories();
        $this->initTerms();
        $this->initComments();
    }
    
    public function initUser() {
        $usersManager = new UsersManager();
        $user         = $usersManager->searchById($this->post->getUserId());
        
        if (empty($user)) {
            throw new \Exception("El usuario no existe.");
        }
        
        $this->userTemplate = new UserTemplate($user);
    }
    
    public function initCategories() {
        $categoriesManager        = new CategoriesManager();
        $this->categoriesTemplate = $categoriesManager->searchByPostId($this->post->getId());
        $this->categoriesTemplate = array_map(function(Category $category) {
            return new CategoryTemplate($category);
        }, $this->categoriesTemplate);
    }
    
    public function initTerms() {
        $termsManager        = new TermsManager();
        $this->termsTemplate = $termsManager->searchByPostId($this->post->getId());
        $this->termsTemplate = array_map(function(Term $term) {
            return new TermTemplate($term);
        }, $this->termsTemplate);
    }
    
    public function initComments() {
        $commentsManager        = new CommentsManager();
        $this->commentsTemplate = $commentsManager->searchByPostId($this->post->getId());
        $this->commentsTemplate = array_map(function(Comment $comment) {
            return new CommentTemplate($comment);
        }, $this->commentsTemplate);
    }
    
    /**
     * @param int $postId
     *
     * @throws \Exception
     */
    public function initPost($postId) {
        $postsManager = new PostsManager();
        $this->post   = $postsManager->searchById($postId);
        
        if ($this->post === FALSE) {
            throw new \Exception("La entrada no existe.");
        }
    }
    
    /**
     * @return Post
     */
    public function getPost() {
        return $this->post;
    }
    
    /**
     * @param Post $post
     */
    public function setPost($post) {
        $this->post = $post;
    }
    
    /**
     * @return array
     */
    public function getCategoriesTemplate() {
        return $this->categoriesTemplate;
    }
    
    /**
     * @return array
     */
    public function getTermsTemplate() {
        return $this->termsTemplate;
    }
    
    /**
     * @return UserTemplate
     */
    public function getUserTemplate() {
        return $this->userTemplate;
    }
    
    /**
     * @return array
     */
    public function getCommentsTemplate() {
        return $this->commentsTemplate;
    }
    
}
