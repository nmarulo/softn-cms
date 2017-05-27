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
    private $postID;
    
    /** @var int */
    private $termID;
    
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
    
    /**
     * @return int
     */
    public function getTermID() {
        return $this->termID;
    }
    
    /**
     * @param int $termID
     */
    public function setTermID($termID) {
        $this->termID = $termID;
    }
    
}
