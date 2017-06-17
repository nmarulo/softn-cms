<?php
/**
 * PostCategory.php
 */

namespace SoftnCMS\models\tables;

/**
 * Class PostCategory
 * @author Nicolás Marulanda P.
 */
class PostCategory {
    
    /** @var int */
    private $postID;
    
    /** @var int */
    private $categoryID;
    
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
    public function getCategoryID() {
        return $this->categoryID;
    }
    
    /**
     * @param int $categoryID
     */
    public function setCategoryID($categoryID) {
        $this->categoryID = $categoryID;
    }
    
}
