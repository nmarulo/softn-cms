<?php
/**
 * PostsCategoriesManager.php
 */

namespace SoftnCMS\models\managers;

use SoftnCMS\models\tables\PostCategory;
use SoftnCMS\util\Arrays;
use SoftnCMS\util\database\ManagerAbstract;

/**
 * Class PostsCategoriesManager
 * @author Nicolás Marulanda P.
 */
class PostsCategoriesManager extends ManagerAbstract {
    
    const TABLE       = 'posts_categories';
    
    const POST_ID     = 'post_id';
    
    const CATEGORY_ID = 'category_id';
    
    public function countPostsByCategoryIdAndPostStatus($categoryId, $postStatus) {
        $tablePosts = $this->getTableWithPrefix(PostsManager::TABLE);
        $table      = $this->getTableWithPrefix();
        $query      = sprintf('SELECT COUNT(*) AS COUNT FROM %1$s WHERE %2$s = :%2$s AND %3$s IN (SELECT %4$s FROM %5$s WHERE %6$s = :%6$s)', $table, self::CATEGORY_ID, self::POST_ID, PostsManager::COLUMN_ID, $tablePosts, PostsManager::POST_STATUS);
        parent::addPrepareStatement(self::CATEGORY_ID, $categoryId, \PDO::PARAM_INT);
        parent::addPrepareStatement(PostsManager::POST_STATUS, $postStatus, \PDO::PARAM_INT);
        $result = Arrays::findFirst(parent::getDB()
                                          ->select($query));
        
        return empty($result) ? 0 : $result;
    }
    
    /**
     * @param PostCategory $object
     *
     * @return bool
     */
    public function create($object) {
        $result = parent::create($object);
        
        if (!empty($result)) {
            $this->updateCategoryPostCount($object->getCategoryId(), 1);
        }
        
        return $result;
    }
    
    private function updateCategoryPostCount($categoryId, $num) {
        $categoriesManager = new CategoriesManager();
        
        return $categoriesManager->updatePostCount($categoryId, $num);
    }
    
    public function searchAllByCategoryId($categoryId) {
        return parent::searchAllByColumn($categoryId, self::CATEGORY_ID, \PDO::PARAM_INT);
    }
    
    public function deleteAllByPostId($postId) {
        $postsCategories = $this->searchAllByPostId($postId);
        $result          = parent::deleteByColumn($postId, self::POST_ID, \PDO::PARAM_INT);
        
        if (!empty($result)) {
            array_walk($postsCategories, function(PostCategory $postCategory) {
                $this->updateCategoryPostCount($postCategory->getCategoryId(), -1);
            });
        }
        
        return $result;
    }
    
    public function searchAllByPostId($postId) {
        return parent::searchAllByColumn($postId, self::POST_ID, \PDO::PARAM_INT);
    }
    
    public function deleteAllByCategoryId($categoryId) {
        $result = parent::deleteByColumn($categoryId, self::CATEGORY_ID, \PDO::PARAM_INT);
        
        if (!empty($result)) {
            $this->updateCategoryPostCount($categoryId, -1);
        }
        
        return $result;
    }
    
    public function deleteByPostAndCategory($postId, $categoryId) {
        parent::addPrepareStatement(self::POST_ID, $postId, \PDO::PARAM_INT);
        parent::addPrepareStatement(self::CATEGORY_ID, $categoryId, \PDO::PARAM_INT);
        $result = parent::deleteByPrepareStatement();
        
        if (!empty($result)) {
            $this->updateCategoryPostCount($categoryId, -1);
        }
        
        return $result;
    }
    
    /**
     * @param PostCategory $object
     */
    protected function prepareStatement($object) {
        parent::addPrepareStatement(self::CATEGORY_ID, $object->getCategoryId(), \PDO::PARAM_INT);
        parent::addPrepareStatement(self::POST_ID, $object->getPostId(), \PDO::PARAM_INT);
    }
    
    protected function getTable() {
        return self::TABLE;
    }
    
    protected function buildObject($result) {
        $postCategory = new PostCategory();
        $postCategory->setPostId(Arrays::get($result, self::POST_ID));
        $postCategory->setCategoryId(Arrays::get($result, self::CATEGORY_ID));
        
        return $postCategory;
    }
    
}
