<?php
/**
 * Comment.php
 */

namespace SoftnCMS\models\tables;

use SoftnCMS\models\TableAbstract;

/**
 * Class Comment
 * @author Nicolás Marulanda P.
 */
class Comment extends TableAbstract {
    
    /** @var int */
    private $commentStatus;
    
    /** @var string */
    private $commentAuthor;
    
    /** @var string */
    private $commentAuthorEmail;
    
    /** @var string */
    private $commentDate;
    
    /** @var string */
    private $commentContents;
    
    /** @var int */
    private $commentUserId;
    
    /** @var int */
    private $postId;
    
    /**
     * @return int
     */
    public function getCommentStatus() {
        return $this->commentStatus;
    }
    
    /**
     * @param int $commentStatus
     */
    public function setCommentStatus($commentStatus) {
        $this->commentStatus = $commentStatus;
    }
    
    /**
     * @return string
     */
    public function getCommentAuthor() {
        return $this->commentAuthor;
    }
    
    /**
     * @param string $commentAuthor
     */
    public function setCommentAuthor($commentAuthor) {
        $this->commentAuthor = $commentAuthor;
    }
    
    /**
     * @return string
     */
    public function getCommentAuthorEmail() {
        return $this->commentAuthorEmail;
    }
    
    /**
     * @param string $commentAuthorEmail
     */
    public function setCommentAuthorEmail($commentAuthorEmail) {
        $this->commentAuthorEmail = $commentAuthorEmail;
    }
    
    /**
     * @return string
     */
    public function getCommentDate() {
        return $this->commentDate;
    }
    
    /**
     * @param string $commentDate
     */
    public function setCommentDate($commentDate) {
        $this->commentDate = $commentDate;
    }
    
    /**
     * @return string
     */
    public function getCommentContents() {
        return $this->commentContents;
    }
    
    /**
     * @param string $commentContents
     */
    public function setCommentContents($commentContents) {
        $this->commentContents = $commentContents;
    }
    
    /**
     * @return int
     */
    public function getCommentUserId() {
        return $this->commentUserId;
    }
    
    /**
     * @param int $commentUserId
     */
    public function setCommentUserId($commentUserId) {
        $this->commentUserId = $commentUserId;
    }
    
    /**
     * @return int
     */
    public function getPostId() {
        return $this->postId;
    }
    
    /**
     * @param int $postId
     */
    public function setPostId($postId) {
        $this->postId = $postId;
    }
    
}
