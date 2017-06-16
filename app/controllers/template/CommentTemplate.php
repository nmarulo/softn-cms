<?php
/**
 * CommentTemplate.php
 */

namespace SoftnCMS\controllers\template;

use SoftnCMS\controllers\Template;
use SoftnCMS\models\managers\CommentsManager;
use SoftnCMS\models\managers\PostsManager;
use SoftnCMS\models\tables\Comment;

/**
 * Class CommentTemplate
 * @author Nicolás Marulanda P.
 */
class CommentTemplate extends Template {
    
    /** @var Comment */
    private $comment;
    
    /** @var PostTemplate */
    private $post;
    
    /**
     * CommentTemplate constructor.
     *
     * @param Comment $comment
     * @param bool    $initRelationship
     */
    public function __construct(Comment $comment = NULL, $initRelationship = FALSE) {
        $this->comment = $comment;
        $this->post    = NULL;
        
        if ($initRelationship) {
            $this->initRelationship();
        }
    }
    
    public function initRelationship() {
        $this->initPost();
    }
    
    private function initPost() {
        $postsManager = new PostsManager();
        $post         = $postsManager->searchByCommentId($this->comment->getId());
        
        if (empty($post)) {
            throw new \Exception('La entrada no existe.');
        }
        
        $this->post = new PostTemplate($post);
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
