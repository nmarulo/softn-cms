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
    private $commentAutor;
    
    /** @var string */
    private $commentAuthorEmail;
    
    /** @var string */
    private $commentDate;
    
    /** @var string */
    private $commentContents;
    
    /** @var int */
    private $commentUserID;
    
    /** @var int */
    private $postID;
    
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
    public function getCommentAutor() {
        return $this->commentAutor;
    }
    
    /**
     * @param string $commentAutor
     */
    public function setCommentAutor($commentAutor) {
        $this->commentAutor = $commentAutor;
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
    public function getCommentUserID() {
        return $this->commentUserID;
    }
    
    /**
     * @param int $commentUserID
     */
    public function setCommentUserID($commentUserID) {
        $this->commentUserID = $commentUserID;
    }
    
    /**
     * @return int
     */
    public function getPostID() {
        return $this->postID;
    }
    
    /**
     * @param int $postID
     */
    public function setPostID($postID) {
        $this->postID = $postID;
    }
    
}
