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
    private $postId;
    
    /** @var int */
    private $categoryId;
    
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
    public function getCategoryId() {
        return $this->categoryId;
    }
    
    /**
     * @param int $categoryId
     */
    public function setCategoryId($categoryId) {
        $this->categoryId = $categoryId;
    }
    
}
