<?php
/**
 * CommentsManager.php
 */

namespace SoftnCMS\models\managers;

use SoftnCMS\models\tables\Comment;
use SoftnCMS\util\Arrays;
use SoftnCMS\util\database\DBInterface;
use SoftnCMS\util\database\ManagerAbstract;

/**
 * Class CommentsManager
 * @author Nicolás Marulanda P.
 */
class CommentsManager extends ManagerAbstract {
    
    const TABLE                = 'comments';
    
    const COMMENT_STATUS       = 'comment_status';
    
    const COMMENT_AUTHOR       = 'comment_autor';
    
    const COMMENT_AUTHOR_EMAIL = 'comment_author_email';
    
    const COMMENT_DATE         = 'comment_date';
    
    const COMMENT_CONTENTS     = 'comment_contents';
    
    const COMMENT_USER_ID      = 'comment_user_id';
    
    const POST_ID              = 'post_id';
    
    public function searchByPostId($postId) {
        return parent::searchAllByColumn($postId, self::POST_ID, \PDO::PARAM_INT, ['ORDER BY ' . self::COLUMN_ID . ' DESC']);
    }
    
    public function searchByPostIdAndStatus($postId, $status) {
        parent::addPrepareStatement(self::POST_ID, $postId, \PDO::PARAM_INT);
        parent::addPrepareStatement(self::COMMENT_STATUS, $status, \PDO::PARAM_INT);
        $query = 'SELECT * FROM %1$s WHERE %2$s = :%2$s AND %3$s = :%3$s ORDER BY %4$s DESC';
        $query = sprintf($query, $this->getTableWithPrefix(), self::COMMENT_STATUS, self::POST_ID, self::COLUMN_ID);
        
        return parent::search($query);
    }
    
    public function delete($id) {
        $postsManager = new PostsManager($this->getConnection());
        $post         = $postsManager->searchByCommentId($id);
        $result       = parent::deleteById($id);
        
        if ($result) {
            $postsManager->updateCommentCount($post->getId(), -1);
        }
        
        return $result;
    }
    
    /**
     * @param Comment $object
     *
     * @return bool
     */
    public function create($object) {
        $result = parent::create($object);
        
        if ($result) {
            $postsManager = new PostsManager($this->getConnection());
            $postsManager->updateCommentCount($object->getPostId(), 1);
        }
        
        return $result;
    }
    
    public function searchByUserId($userId) {
        return parent::searchAllByColumn($userId, self::COMMENT_USER_ID, \PDO::PARAM_INT, ['ORDER BY ' . self::COLUMN_ID . ' DESC']);
    }
    
    /**
     * @param Comment $object
     */
    protected function prepareStatement($object) {
        parent::addPrepareStatement(self::COLUMN_ID, $object->getId(), \PDO::PARAM_INT);
        parent::addPrepareStatement(self::COMMENT_STATUS, $object->getCommentStatus(), \PDO::PARAM_INT);
        parent::addPrepareStatement(self::COMMENT_AUTHOR, $object->getCommentAuthor(), \PDO::PARAM_STR);
        parent::addPrepareStatement(self::COMMENT_AUTHOR_EMAIL, $object->getCommentAuthorEmail(), \PDO::PARAM_STR);
        parent::addPrepareStatement(self::COMMENT_CONTENTS, $object->getCommentContents(), \PDO::PARAM_STR);
        parent::addPrepareStatement(self::COMMENT_DATE, $object->getCommentDate(), \PDO::PARAM_STR);
        parent::addPrepareStatement(self::COMMENT_USER_ID, $object->getCommentUserId(), \PDO::PARAM_INT);
        parent::addPrepareStatement(self::POST_ID, $object->getPostId(), \PDO::PARAM_INT);
    }
    
    protected function getTable() {
        return self::TABLE;
    }
    
    protected function buildObject($result) {
        $comment = new Comment();
        $comment->setId(Arrays::get($result, self::COLUMN_ID));
        $comment->setPostId(Arrays::get($result, self::POST_ID));
        $comment->setCommentUserId(Arrays::get($result, self::COMMENT_USER_ID));
        $comment->setCommentDate(Arrays::get($result, self::COMMENT_DATE));
        $comment->setCommentContents(Arrays::get($result, self::COMMENT_CONTENTS));
        $comment->setCommentAuthorEmail(Arrays::get($result, self::COMMENT_AUTHOR_EMAIL));
        $comment->setCommentStatus(Arrays::get($result, self::COMMENT_STATUS));
        $comment->setCommentAuthor(Arrays::get($result, self::COMMENT_AUTHOR));
        
        return $comment;
    }
    
}
