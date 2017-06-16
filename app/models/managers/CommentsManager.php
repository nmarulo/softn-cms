<?php
/**
 * CommentsManager.php
 */

namespace SoftnCMS\models\managers;

use SoftnCMS\models\CRUDManagerAbstract;
use SoftnCMS\models\tables\Comment;
use SoftnCMS\util\Arrays;

/**
 * Class CommentsManager
 * @author Nicolás Marulanda P.
 */
class CommentsManager extends CRUDManagerAbstract {
    
    const TABLE                = 'comments';
    
    const COMMENT_STATUS       = 'comment_status';
    
    const COMMENT_AUTHOR       = 'comment_autor';
    
    const COMMENT_AUTHOR_EMAIL = 'comment_author_email';
    
    const COMMENT_DATE         = 'comment_date';
    
    const COMMENT_CONTENTS     = 'comment_contents';
    
    const COMMENT_USER_ID      = 'comment_user_ID';
    
    const POST_ID              = 'post_ID';
    
    public function searchByPostId($postId) {
        parent::parameterQuery(self::POST_ID, $postId, \PDO::PARAM_INT);
        
        return parent::searchAllBy(self::POST_ID);
    }
    
    /**
     * @param Comment $object
     */
    protected function addParameterQuery($object) {
        parent::parameterQuery(self::COMMENT_STATUS, $object->getCommentStatus(), \PDO::PARAM_INT);
        parent::parameterQuery(self::COMMENT_AUTHOR, $object->getCommentAutor(), \PDO::PARAM_STR);
        parent::parameterQuery(self::COMMENT_AUTHOR_EMAIL, $object->getCommentAuthorEmail(), \PDO::PARAM_STR);
        parent::parameterQuery(self::COMMENT_CONTENTS, $object->getCommentContents(), \PDO::PARAM_STR);
        parent::parameterQuery(self::COMMENT_DATE, $object->getCommentDate(), \PDO::PARAM_STR);
        parent::parameterQuery(self::COMMENT_USER_ID, $object->getCommentUserID(), \PDO::PARAM_INT);
        parent::parameterQuery(self::POST_ID, $object->getPostID(), \PDO::PARAM_INT);
    }
    
    protected function getTable() {
        return self::TABLE;
    }
    
    protected function buildObjectTable($result) {
        parent::buildObjectTable($result);
        $comment = new Comment();
        $comment->setId(Arrays::get($result, self::ID));
        $comment->setPostID(Arrays::get($result, self::POST_ID));
        $comment->setCommentUserID(Arrays::get($result, self::COMMENT_USER_ID));
        $comment->setCommentDate(Arrays::get($result, self::COMMENT_DATE));
        $comment->setCommentContents(Arrays::get($result, self::COMMENT_CONTENTS));
        $comment->setCommentAuthorEmail(Arrays::get($result, self::COMMENT_AUTHOR_EMAIL));
        $comment->setCommentStatus(Arrays::get($result, self::COMMENT_STATUS));
        $comment->setCommentAutor(Arrays::get($result, self::COMMENT_AUTHOR));
        
        return $comment;
    }
    
}
