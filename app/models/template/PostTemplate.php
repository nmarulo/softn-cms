<?php
/**
 * PostTemplate.php
 */

namespace SoftnCMS\models\template;

use SoftnCMS\classes\constants\OptionConstants;
use SoftnCMS\models\TemplateAbstract;
use SoftnCMS\models\managers\CategoriesManager;
use SoftnCMS\models\managers\CommentsManager;
use SoftnCMS\models\managers\OptionsManager;
use SoftnCMS\models\managers\PostsManager;
use SoftnCMS\models\managers\TermsManager;
use SoftnCMS\models\managers\UsersManager;
use SoftnCMS\models\tables\Category;
use SoftnCMS\models\tables\Comment;
use SoftnCMS\models\tables\Post;
use SoftnCMS\models\tables\Term;
use SoftnCMS\util\database\DBInterface;
use SoftnCMS\util\Escape;
use SoftnCMS\util\Logger;

/**
 * Class PostTemplate
 * @author Nicolás Marulanda P.
 */
class PostTemplate extends TemplateAbstract {
    
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
    
    /** @var bool */
    private $canCommentAnyUser;
    
    /**
     * PostTemplate constructor.
     *
     * @param Post        $post
     * @param bool        $initRelationship
     * @param string      $siteUrl
     * @param DBInterface $connectionDB
     */
    public function __construct(Post $post = NULL, $initRelationship = FALSE, $siteUrl = '', DBInterface $connectionDB = NULL) {
        parent::__construct($siteUrl, $connectionDB);
        $post->setPostContents(Escape::htmlDecode($post->getPostContents()));
        $this->post               = $post;
        $this->categoriesTemplate = [];
        $this->termsTemplate      = [];
        $this->userTemplate       = NULL;
        $optionsManager           = new OptionsManager($this->getConnectionDB());
        $optionComment            = $optionsManager->searchByName(OptionConstants::COMMENT);
        $this->canCommentAnyUser  = !empty($optionComment->getOptionValue());
        
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
        $usersManager = new UsersManager($this->getConnectionDB());
        $user         = $usersManager->searchById($this->post->getUserId());
        
        if (empty($user)) {
            Logger::getInstance()
                  ->error('El usuario no existe.', ['currentUserId' => $this->post->getUserId()]);
            throw new \Exception("El usuario no existe.");
        }
        
        $this->userTemplate = new UserTemplate($user, FALSE, $this->getSiteUrl(), $this->getConnectionDB());
    }
    
    public function initCategories() {
        $categoriesManager        = new CategoriesManager($this->getConnectionDB());
        $this->categoriesTemplate = $categoriesManager->searchByPostId($this->post->getId());
        $this->categoriesTemplate = array_map(function(Category $category) {
            return new CategoryTemplate($category, FALSE, $this->getSiteUrl(), $this->getConnectionDB());
        }, $this->categoriesTemplate);
    }
    
    public function initTerms() {
        $termsManager        = new TermsManager($this->getConnectionDB());
        $this->termsTemplate = $termsManager->searchByPostId($this->post->getId());
        $this->termsTemplate = array_map(function(Term $term) {
            return new TermTemplate($term, FALSE, $this->getSiteUrl(), $this->getConnectionDB());
        }, $this->termsTemplate);
    }
    
    public function initComments() {
        $commentStatus          = TRUE;
        $commentsManager        = new CommentsManager($this->getConnectionDB());
        $this->commentsTemplate = $commentsManager->searchByPostIdAndStatus($this->post->getId(), $commentStatus);
        $this->commentsTemplate = array_map(function(Comment $comment) {
            return new CommentTemplate($comment, FALSE, $this->getSiteUrl(), $this->getConnectionDB());
        }, $this->commentsTemplate);
        
        $this->post->setPostCommentCount(count($this->commentsTemplate));
    }
    
    /**
     * @param int $postId
     *
     * @throws \Exception
     */
    public function initPost($postId) {
        $postsManager = new PostsManager($this->getConnectionDB());
        $this->post   = $postsManager->searchById($postId);
        
        if ($this->post === FALSE) {
            Logger::getInstance()
                  ->error('La entrada no existe.', ['currentPostId' => $postId]);
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
    
    /**
     * @return bool
     */
    public function isCanCommentAnyUser() {
        return $this->canCommentAnyUser;
    }
    
}
