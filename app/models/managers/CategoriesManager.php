<?php
/**
 * CategoriesManager.php
 */

namespace SoftnCMS\models\managers;

use SoftnCMS\models\CRUDManagerAbstract;
use SoftnCMS\models\tables\Category;
use SoftnCMS\models\tables\Post;
use SoftnCMS\util\Arrays;

/**
 * Class CategoriesManager
 * @author Nicolás Marulanda P.
 */
class CategoriesManager extends CRUDManagerAbstract {
    
    const TABLE                = 'categories';
    
    const CATEGORY_NAME        = 'category_name';
    
    const CATEGORY_DESCRIPTION = 'category_description';
    
    const CATEGORY_POST_COUNT  = 'category_post_count';
    
    public function searchByPostId($postId) {
        $columnPostId         = PostsCategoriesManager::POST_ID;
        $tablePostsCategories = parent::getTableWithPrefix(PostsCategoriesManager::TABLE);
        $table                = parent::getTableWithPrefix();
        $query                = 'SELECT * FROM %1$s WHERE %2$s IN (SELECT %3$s FROM %4$s WHERE %5$s = :%5$s)';
        $query                = sprintf($query, $table, self::ID, PostsCategoriesManager::CATEGORY_ID, $tablePostsCategories, $columnPostId);
        $this->parameterQuery($columnPostId, $postId, \PDO::PARAM_INT);
        
        return parent::readData($query);
    }
    
    /**
     * @param Category $object
     *
     * @return bool
     */
    public function create($object) {
        $object = $this->checkName($object);
        
        return parent::create($object);
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
        parent::parameterQuery(self::CATEGORY_NAME, $name, \PDO::PARAM_STR);
        $result = parent::searchBy(self::CATEGORY_NAME);
        
        //Si el "id" es el mismo, estamos actualizando.
        return $result !== FALSE && $result->getId() != $id;
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
        
        return parent::update($object);
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
            parent::parameterQuery($param, $postId, \PDO::PARAM_INT);
            
            return "$columnPostId = :$param";
        }, $postsId);
        
        $strWhere        = implode(' OR ', $where);
        $tablePostsTerms = parent::getTableWithPrefix(PostsCategoriesManager::TABLE);
        $table           = parent::getTableWithPrefix();
        $query           = 'SELECT * FROM %1$s WHERE %2$s IN (SELECT %3$s FROM %4$s WHERE %5$s)';
        $query           = sprintf($query, $table, self::ID, PostsCategoriesManager::CATEGORY_ID, $tablePostsTerms, $strWhere);
        
        return parent::readData($query);
    }
    
    /**
     * @param Category $object
     */
    protected function addParameterQuery($object) {
        parent::parameterQuery(self::CATEGORY_POST_COUNT, $object->getCategoryPostCount(), \PDO::PARAM_INT);
        parent::parameterQuery(self::CATEGORY_NAME, $object->getCategoryName(), \PDO::PARAM_STR);
        parent::parameterQuery(self::CATEGORY_DESCRIPTION, $object->getCategoryDescription(), \PDO::PARAM_STR);
    }
    
    protected function getTable() {
        return self::TABLE;
    }
    
    protected function buildObjectTable($result) {
        parent::buildObjectTable($result);
        $category = new Category();
        $category->setId(Arrays::get($result, self::ID));
        $category->setCategoryPostCount(Arrays::get($result, self::CATEGORY_POST_COUNT));
        $category->setCategoryDescription(Arrays::get($result, self::CATEGORY_DESCRIPTION));
        $category->setCategoryName(Arrays::get($result, self::CATEGORY_NAME));
        
        return $category;
    }
    
}
