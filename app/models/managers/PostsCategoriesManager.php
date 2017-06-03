<?php
/**
 * PostsCategoriesManager.php
 */

namespace SoftnCMS\models\managers;

use SoftnCMS\models\CRUDManagerAbstract;
use SoftnCMS\models\tables\PostCategory;
use SoftnCMS\util\Arrays;

/**
 * Class PostsCategoriesManager
 * @author Nicolás Marulanda P.
 */
class PostsCategoriesManager extends CRUDManagerAbstract {
    
    const TABLE       = 'posts_categories';
    
    const POST_ID     = 'post_ID';
    
    const CATEGORY_ID = 'category_ID';
    
    public function searchAllByPostId($postId) {
        parent::parameterQuery(self::POST_ID, $postId, \PDO::PARAM_INT);
        
        return parent::searchAllBy(self::POST_ID);
    }
    
    public function searchAllByCategoryId($categoryId) {
        parent::parameterQuery(self::CATEGORY_ID, $categoryId, \PDO::PARAM_INT);
        
        return parent::searchAllBy(self::CATEGORY_ID);
    }
    
    public function deleteAllByPostId($postId) {
        parent::parameterQuery(self::POST_ID, $postId, \PDO::PARAM_INT);
        
        return parent::deleteBy();
    }
    
    public function deleteAllByCategoryId($categoryId) {
        parent::parameterQuery(self::CATEGORY_ID, $categoryId, \PDO::PARAM_INT);
        
        return parent::deleteBy();
    }
    
    public function deleteByPostAndCategory($postId, $categoryId) {
        parent::parameterQuery(self::POST_ID, $postId, \PDO::PARAM_INT);
        parent::parameterQuery(self::CATEGORY_ID, $categoryId, \PDO::PARAM_INT);
        
        return parent::deleteBy();
    }
    
    /**
     * @param PostCategory $object
     */
    protected function addParameterQuery($object) {
        parent::parameterQuery(self::CATEGORY_ID, $object->getCategoryID(), \PDO::PARAM_INT);
        parent::parameterQuery(self::POST_ID, $object->getPostID(), \PDO::PARAM_INT);
    }
    
    protected function getTable() {
        return self::TABLE;
    }
    
    protected function buildObjectTable($result) {
        parent::buildObjectTable($result);
        $postCategory = new PostCategory();
        $postCategory->setPostID(Arrays::get($result, self::POST_ID));
        $postCategory->setCategoryID(Arrays::get($result, self::CATEGORY_ID));
        
        return $postCategory;
    }
    
}
