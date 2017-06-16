<?php
/**
 * CategoriesManager.php
 */

namespace SoftnCMS\models\managers;

use SoftnCMS\models\CRUDManagerAbstract;
use SoftnCMS\models\tables\Category;
use SoftnCMS\models\tables\Post;
use SoftnCMS\util\Arrays;
use SoftnCMS\util\MySQL;

/**
 * Class CategoriesManager
 * @author Nicolás Marulanda P.
 */
class CategoriesManager extends CRUDManagerAbstract {
    
    const TABLE                = 'categories';
    
    const CATEGORY_NAME        = 'category_name';
    
    const CATEGORY_DESCRIPTION = 'category_description';
    
    const CATEGORY_COUNT       = 'category_count';
    
    public function searchByPostId($postId) {
        $table                = parent::getTableWithPrefix(self::TABLE);
        $tablePostsCategories = parent::getTableWithPrefix(PostsCategoriesManager::TABLE);
        $query                = 'SELECT * ';
        $query                .= "FROM $table ";
        $query                .= 'WHERE id IN (';
        $query                .= 'SELECT category_ID ';
        $query                .= "FROM $tablePostsCategories ";
        $query                .= 'WHERE post_ID = :post_ID)';
        $this->parameterQuery(PostsCategoriesManager::POST_ID, $postId, \PDO::PARAM_INT);
        
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
     * @return array
     */
    public function searchByPosts($posts) {
        $postId = array_map(function(Post $post) {
            return $post->getId();
        }, $posts);
        
        
        
        $where = array_map(function($userId) {
            $param = self::ID . "_$userId";
            parent::parameterQuery($param, $userId, \PDO::PARAM_INT);
        
            return self::ID . ' = :' . $param;
        }, $postId);
    
        $query = 'SELECT * ';
        $query .= 'FROM ' . parent::getTableWithPrefix();
        $query .= ' WHERE ';
        $query .= implode(" OR ", $where);
    
        return parent::readData($query);
    }
    
    /**
     * @param Category $object
     */
    protected function addParameterQuery($object) {
        parent::parameterQuery(self::CATEGORY_COUNT, $object->getCategoryCount(), \PDO::PARAM_INT);
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
        $category->setCategoryCount(Arrays::get($result, self::CATEGORY_COUNT));
        $category->setCategoryDescription(Arrays::get($result, self::CATEGORY_DESCRIPTION));
        $category->setCategoryName(Arrays::get($result, self::CATEGORY_NAME));
        
        return $category;
    }
    
}
