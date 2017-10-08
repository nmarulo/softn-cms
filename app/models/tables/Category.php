<?php
/**
 * Category.php
 */

namespace SoftnCMS\models\tables;

use SoftnCMS\util\database\TableAbstract;

/**
 * Class Category
 * @author Nicolás Marulanda P.
 */
class Category extends TableAbstract {
    
    /** @var string */
    private $categoryName;
    
    /** @var string */
    private $categoryDescription;
    
    /** @var int */
    private $categoryPostCount;
    
    /**
     * @return string
     */
    public function getCategoryName() {
        return $this->categoryName;
    }
    
    /**
     * @param string $categoryName
     */
    public function setCategoryName($categoryName) {
        $this->categoryName = $categoryName;
    }
    
    /**
     * @return string
     */
    public function getCategoryDescription() {
        return $this->categoryDescription;
    }
    
    /**
     * @param string $categoryDescription
     */
    public function setCategoryDescription($categoryDescription) {
        $this->categoryDescription = $categoryDescription;
    }
    
    /**
     * @return int
     */
    public function getCategoryPostCount() {
        return $this->categoryPostCount;
    }
    
    /**
     * @param int $categoryPostCount
     */
    public function setCategoryPostCount($categoryPostCount) {
        $this->categoryPostCount = $categoryPostCount;
    }
    
}
