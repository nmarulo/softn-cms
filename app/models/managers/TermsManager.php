<?php
/**
 * TermsManager.php
 */

namespace SoftnCMS\models\managers;

use SoftnCMS\models\tables\Post;
use SoftnCMS\models\tables\Term;
use SoftnCMS\util\Arrays;
use SoftnCMS\util\database\ManagerAbstract;

/**
 * Class TermsManager
 * @author Nicolás Marulanda P.
 */
class TermsManager extends ManagerAbstract {
    
    const TABLE            = 'terms';
    
    const TERM_NAME        = 'term_name';
    
    const TERM_DESCRIPTION = 'term_description';
    
    const TERM_POST_COUNT  = 'term_post_count';
    
    /**
     * @param Term $object
     *
     * @return bool
     */
    public function create($object) {
        $object = $this->checkName($object);
        
        return parent::create($object);
    }
    
    /**
     * @param Term $object
     *
     * @return Term
     */
    private function checkName($object) {
        $name    = $object->getTermName();
        $id      = $object->getId();
        $newName = $name;
        $num     = 0;
        
        while ($this->nameExists($newName, $id)) {
            $newName = $name . ++$num;
        }
        
        $object->setTermName($newName);
        
        return $object;
    }
    
    /**
     * @param string $name
     * @param int    $id
     *
     * @return bool
     */
    private function nameExists($name, $id) {
        $result = parent::searchAllByColumn($name, self::TERM_NAME, \PDO::PARAM_STR);
        $result = Arrays::findFirst($result);
        
        //Si el "id" es el mismo, estamos actualizando.
        return !empty($result) && $result->getId() != $id;
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
            $columnPostId = PostsTermsManager::POST_ID;
            $param        = $columnPostId . "_$postId";
            parent::addPrepareStatement($param, $postId, \PDO::PARAM_INT);
            
            return "$columnPostId = :$param";
        }, $postsId);
        
        $tablePostsTerms = parent::getTableWithPrefix(PostsTermsManager::TABLE);
        $strWhere        = implode(' OR ', $where);
        $query           = 'SELECT * FROM %1$s WHERE %2$s IN (SELECT %3$s FROM %4$s WHERE %5$s)';
        $query           = sprintf($query, parent::getTableWithPrefix(), self::COLUMN_ID, PostsTermsManager::TERM_ID, $tablePostsTerms, $strWhere);
        
        return parent::search($query);
    }
    
    public function searchByPostId($postId) {
        $tablePostsTerms = parent::getTableWithPrefix(PostsTermsManager::TABLE);
        $query           = 'SELECT * FROM %1$s WHERE %2$s IN (SELECT %3$s FROM %4$s WHERE %5$s = :%5$s)';
        $query           = sprintf($query, parent::getTableWithPrefix(), self::COLUMN_ID, PostsTermsManager::TERM_ID, $tablePostsTerms, PostsTermsManager::POST_ID);
        parent::addPrepareStatement(PostsTermsManager::POST_ID, $postId, \PDO::PARAM_INT);
        
        return parent::search($query);
    }
    
    public function updatePostCount($termId, $num) {
        $term = $this->searchById($termId);
        $term->setTermPostCount($term->getTermPostCount() + $num);
        
        return parent::updateByColumnId($term);
    }
    
    /**
     * @param Term $object
     *
     * @return bool
     */
    public function update($object) {
        $object = $this->checkName($object);
        
        return parent::updateByColumnId($object);
    }
    
    /**
     * @param Term $object
     */
    protected function prepareStatement($object) {
        parent::addPrepareStatement(self::COLUMN_ID, $object->getId(), \PDO::PARAM_INT);
        parent::addPrepareStatement(self::TERM_NAME, $object->getTermName(), \PDO::PARAM_STR);
        parent::addPrepareStatement(self::TERM_DESCRIPTION, $object->getTermDescription(), \PDO::PARAM_STR);
        parent::addPrepareStatement(self::TERM_POST_COUNT, $object->getTermPostCount(), \PDO::PARAM_INT);
    }
    
    protected function getTable() {
        return self::TABLE;
    }
    
    protected function buildObject($result) {
        $term = new Term();
        $term->setId(Arrays::get($result, self::COLUMN_ID));
        $term->setTermName(Arrays::get($result, self::TERM_NAME));
        $term->setTermDescription(Arrays::get($result, self::TERM_DESCRIPTION));
        $term->setTermPostCount(Arrays::get($result, self::TERM_POST_COUNT));
        
        return $term;
    }
    
}
