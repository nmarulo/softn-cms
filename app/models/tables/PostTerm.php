<?php
/**
 * PostTerm.php
 */

namespace SoftnCMS\models\tables;

/**
 * Class PostTerm
 * @author Nicolás Marulanda P.
 */
class PostTerm {
    
    /** @var int */
    private $postId;
    
    /** @var int */
    private $termId;
    
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
    
    /**
     * @return int
     */
    public function getTermId() {
        return $this->termId;
    }
    
    /**
     * @param int $termId
     */
    public function setTermId($termId) {
        $this->termId = $termId;
    }
    
}
