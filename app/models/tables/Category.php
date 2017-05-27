<?php
/**
 * Category.php
 */

namespace SoftnCMS\models\tables;

use SoftnCMS\models\TableAbstract;

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
    private $categoryCount;
    
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
    public function getCategoryCount() {
        return $this->categoryCount;
    }
    
    /**
     * @param int $categoryCount
     */
    public function setCategoryCount($categoryCount) {
        $this->categoryCount = $categoryCount;
    }
    
}
