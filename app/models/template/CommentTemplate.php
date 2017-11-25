<?php
/**
 * CommentTemplate.php
 */

namespace SoftnCMS\models\template;

use SoftnCMS\classes\constants\OptionConstants;
use SoftnCMS\models\TemplateAbstract;
use SoftnCMS\models\managers\CommentsManager;
use SoftnCMS\models\managers\OptionsManager;
use SoftnCMS\models\managers\PostsManager;
use SoftnCMS\models\managers\UsersManager;
use SoftnCMS\models\tables\Comment;
use SoftnCMS\util\Escape;
use SoftnCMS\util\Logger;

/**
 * Class CommentTemplate
 * @author Nicolás Marulanda P.
 */
class CommentTemplate extends TemplateAbstract {
    
    /** @var Comment */
    private $comment;
    
    /** @var PostTemplate */
    private $post;
    
    /** @var UserTemplate */
    private $userTemplate;
    
    /** @var string */
    private $defaultUserImage;
    
    /**
     * CommentTemplate constructor.
     *
     * @param Comment $comment
     * @param bool    $initRelationship
     */
    public function __construct(Comment $comment = NULL, $initRelationship = FALSE) {
        parent::__construct();
        $comment->setCommentContents(Escape::htmlDecode($comment->getCommentContents()));
        $this->comment          = $comment;
        $this->post             = NULL;
        $this->userTemplate     = NULL;
        $optionsManager         = new OptionsManager($this->getConnectionDB());
        $optionGravatar         = $optionsManager->searchByName(OptionConstants::GRAVATAR);
        $gravatar               = unserialize($optionGravatar->getOptionValue());
        $this->defaultUserImage = $gravatar->get();
        
        if ($initRelationship) {
            $this->initRelationship();
        }
    }
    
    public function initRelationship() {
        $this->initPost();
        $this->initUser();
    }
    
    public function initPost() {
        $postsManager = new PostsManager($this->getConnectionDB());
        $post         = $postsManager->searchByCommentId($this->comment->getId());
        
        if (empty($post)) {
            Logger::getInstance()
                  ->error('La entrada no existe.', ['currentPostId' => $this->comment->getId()]);
            throw new \Exception('La entrada no existe.');
        }
        
        $this->post = new PostTemplate($post);
    }
    
    public function initUser() {
        $usersManager = new UsersManager($this->getConnectionDB());
        $user         = $usersManager->searchById($this->comment->getCommentUserId());
        
        //No lanza exception ya que un usuario no registrado puede comentar.
        //TODO: agregar a la pagina de opciones si un usuario no registrado puede comentar.
        if (!empty($user)) {
            $this->userTemplate = new UserTemplate($user);
        }
    }
    
    /**
     * @return UserTemplate
     */
    public function getUserTemplate() {
        return $this->userTemplate;
    }
    
    /**
     * @param int $commentId
     *
     * @throws \Exception
     */
    public function initComment($commentId) {
        $commentsManager = new CommentsManager($this->getConnectionDB());
        $this->comment   = $commentsManager->searchById($commentId);
        
        if (empty($this->comment)) {
            Logger::getInstance()
                  ->error('El comentario no existe.', ['currentCommentId' => $commentId]);
            throw new \Exception('El comentario no existe.');
        }
    }
    
    /**
     * @return Comment
     */
    public function getComment() {
        return $this->comment;
    }
    
    /**
     * @return PostTemplate
     */
    public function getPost() {
        return $this->post;
    }
    
    /**
     * @return string
     */
    public function getDefaultUserImage() {
        return $this->defaultUserImage;
    }
    
}
