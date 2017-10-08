<?php
/**
 * CategoriesManager.php
 */

namespace SoftnCMS\models\managers;

use SoftnCMS\models\tables\Category;
use SoftnCMS\models\tables\Post;
use SoftnCMS\util\Arrays;
use SoftnCMS\util\database\ManagerAbstract;

/**
 * Class CategoriesManager
 * @author Nicolás Marulanda P.
 */
class CategoriesManager extends ManagerAbstract {
    
    const TABLE                = 'categories';
    
    const CATEGORY_NAME        = 'category_name';
    
    const CATEGORY_DESCRIPTION = 'category_description';
    
    const CATEGORY_POST_COUNT  = 'category_post_count';
    
    public function searchByPostId($postId) {
        $columnPostId         = PostsCategoriesManager::POST_ID;
        $tablePostsCategories = parent::getTableWithPrefix(PostsCategoriesManager::TABLE);
        $table                = parent::getTableWithPrefix();
        $query                = 'SELECT * FROM %1$s WHERE %2$s IN (SELECT %3$s FROM %4$s WHERE %5$s = :%5$s)';
        $query                = sprintf($query, $table, self::COLUMN_ID, PostsCategoriesManager::CATEGORY_ID, $tablePostsCategories, $columnPostId);
        parent::addPrepareStatement($columnPostId, $postId, \PDO::PARAM_INT);
        
        return parent::search($query);
    }
    
    /**
     * @param Category $object
     *
     * @return bool
     */
    public function create($object) {
        $object = $this->checkName($object);
        
        return parent::saveNew($object);
    }
    
    /**
     * @param Category $object
     *
     * @return Category
     */
    private function checkName($object) {
        $name    = $object->getCategoryName();
        $id      = $object->getId();
        $newName = $name;
        $num     = 0;
        
        while ($this->nameExists($newName, $id)) {
            $newName = $name . ++$num;
        }
        
        $object->setCategoryName($newName);
        
        return $object;
    }
    
    /**
     * @param string $name
     * @param int    $id
     *
     * @return bool
     */
    private function nameExists($name, $id) {
        $result = parent::searchAllByColumn($name, self::CATEGORY_NAME, \PDO::PARAM_STR);
        $result = Arrays::findFirst($result);
        
        //Si el "id" es el mismo, estamos actualizando.
        return !empty($result) && $result->getId() != $id;
    }
    
    public function updatePostCount($categoryId, $num) {
        $category = $this->searchById($categoryId);
        $category->setCategoryPostCount($category->getCategoryPostCount() + $num);
        
        return $this->update($category);
    }
    
    /**
     * @param Category $object
     *
     * @return bool
     */
    public function update($object) {
        $object = $this->checkName($object);
        
        return parent::updateByColumnId($object);
    }
    
    /**
     * @param array $posts
     *
     * @return array
     */
    public function searchByPosts($posts) {
        $postsId = array_map(function(Post $post) {
            return $post->getId();
        }, $posts);
        
        $where = array_map(function($postId) {
            $columnPostId = PostsCategoriesManager::POST_ID;
            $param        = $columnPostId . "_$postId";
            parent::addPrepareStatement($param, $postId, \PDO::PARAM_INT);
            
            return "$columnPostId = :$param";
        }, $postsId);
        
        $strWhere        = implode(' OR ', $where);
        $tablePostsTerms = parent::getTableWithPrefix(PostsCategoriesManager::TABLE);
        $table           = parent::getTableWithPrefix();
        $query           = 'SELECT * FROM %1$s WHERE %2$s IN (SELECT %3$s FROM %4$s WHERE %5$s)';
        $query           = sprintf($query, $table, self::COLUMN_ID, PostsCategoriesManager::CATEGORY_ID, $tablePostsTerms, $strWhere);
        
        return parent::search($query);
    }
    
    /**
     * @param Category $object
     */
    protected function prepareStatement($object) {
        parent::addPrepareStatement(self::COLUMN_ID, $object->getId(), \PDO::PARAM_INT);
        parent::addPrepareStatement(self::CATEGORY_POST_COUNT, $object->getCategoryPostCount(), \PDO::PARAM_INT);
        parent::addPrepareStatement(self::CATEGORY_NAME, $object->getCategoryName(), \PDO::PARAM_STR);
        parent::addPrepareStatement(self::CATEGORY_DESCRIPTION, $object->getCategoryDescription(), \PDO::PARAM_STR);
    }
    
    protected function getTable() {
        return self::TABLE;
    }
    
    protected function buildObject($result) {
        $category = new Category();
        $category->setId(Arrays::get($result, self::COLUMN_ID));
        $category->setCategoryPostCount(Arrays::get($result, self::CATEGORY_POST_COUNT));
        $category->setCategoryDescription(Arrays::get($result, self::CATEGORY_DESCRIPTION));
        $category->setCategoryName(Arrays::get($result, self::CATEGORY_NAME));
        
        return $category;
    }
    
}
