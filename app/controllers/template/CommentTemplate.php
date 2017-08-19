<?php
/**
 * CommentTemplate.php
 */

namespace SoftnCMS\controllers\template;

use SoftnCMS\controllers\Template;
use SoftnCMS\models\managers\CommentsManager;
use SoftnCMS\models\managers\PostsManager;
use SoftnCMS\models\managers\UsersManager;
use SoftnCMS\models\tables\Comment;
use SoftnCMS\util\Escape;

/**
 * Class CommentTemplate
 * @author Nicolás Marulanda P.
 */
class CommentTemplate extends Template {
    
    /** @var Comment */
    private $comment;
    
    /** @var PostTemplate */
    private $post;
    
    /** @var UserTemplate */
    private $userTemplate;
    
    /**
     * CommentTemplate constructor.
     *
     * @param Comment $comment
     * @param bool    $initRelationship
     */
    public function __construct(Comment $comment = NULL, $initRelationship = FALSE) {
        parent::__construct();
        $comment->setCommentContents(Escape::htmlDecode($comment->getCommentContents()));
        $this->comment      = $comment;
        $this->post         = NULL;
        $this->userTemplate = NULL;
        
        if ($initRelationship) {
            $this->initRelationship();
        }
    }
    
    public function initRelationship() {
        $this->initPost();
        $this->initUser();
    }
    
    public function initPost() {
        $postsManager = new PostsManager();
        $post         = $postsManager->searchByCommentId($this->comment->getId());
        
        if (empty($post)) {
            throw new \Exception('La entrada no existe.');
        }
        
        $this->post = new PostTemplate($post);
    }
    
    public function initUser() {
        $usersManager = new UsersManager();
        $user         = $usersManager->searchById($this->comment->getCommentUserId());
        
        //No lanza exception ya que un usuario no registrado puede comentar.
        //TODO: agregar a la pagina de opciones.
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
        $commentsManager = new CommentsManager();
        $this->comment   = $commentsManager->searchById($commentId);
        
        if (empty($this->comment)) {
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
    
}
