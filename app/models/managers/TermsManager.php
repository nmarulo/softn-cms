<?php
/**
 * TermsManager.php
 */

namespace SoftnCMS\models\managers;

use SoftnCMS\models\CRUDManagerAbstract;
use SoftnCMS\models\tables\Post;
use SoftnCMS\models\tables\Term;
use SoftnCMS\util\Arrays;

/**
 * Class TermsManager
 * @author Nicolás Marulanda P.
 */
class TermsManager extends CRUDManagerAbstract {
    
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
        parent::parameterQuery(self::TERM_NAME, $name, \PDO::PARAM_STR);
        $result = parent::searchBy(self::TERM_NAME);
        
        //Si el "id" es el mismo, estamos actualizando.
        return $result !== FALSE && $result->getId() != $id;
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
            parent::parameterQuery($param, $postId, \PDO::PARAM_INT);
            
            return "$columnPostId = :$param";
        }, $postsId);
        
        $tablePostsTerms = parent::getTableWithPrefix(PostsTermsManager::TABLE);
        $strWhere        = implode(' OR ', $where);
        $query           = 'SELECT * FROM %1$s WHERE %2$s IN (SELECT %3$s FROM %4$s WHERE %5$s)';
        $query           = sprintf($query, parent::getTableWithPrefix(), self::ID, PostsTermsManager::TERM_ID, $tablePostsTerms, $strWhere);
        
        return parent::readData($query);
    }
    
    public function searchByPostId($postId) {
        $tablePostsTerms = parent::getTableWithPrefix(PostsTermsManager::TABLE);
        $query           = 'SELECT * FROM %1$s WHERE %2$s IN (SELECT %3$s FROM %4$s WHERE %5$s = :%5$s)';
        $query           = sprintf($query, parent::getTableWithPrefix(), self::ID, PostsTermsManager::TERM_ID, $tablePostsTerms, PostsTermsManager::POST_ID);
        $this->parameterQuery(PostsTermsManager::POST_ID, $postId, \PDO::PARAM_INT);
        
        return parent::readData($query);
    }
    
    public function updatePostCount($termId, $num) {
        $term = $this->searchById($termId);
        $term->setTermPostCount($term->getTermPostCount() + $num);
        
        return $this->update($term);
    }
    
    /**
     * @param Term $object
     *
     * @return bool
     */
    public function update($object) {
        $object = $this->checkName($object);
        
        return parent::update($object);
    }
    
    /**
     * @param Term $object
     */
    protected function addParameterQuery($object) {
        parent::parameterQuery(self::TERM_NAME, $object->getTermName(), \PDO::PARAM_STR);
        parent::parameterQuery(self::TERM_DESCRIPTION, $object->getTermDescription(), \PDO::PARAM_STR);
        parent::parameterQuery(self::TERM_POST_COUNT, $object->getTermPostCount(), \PDO::PARAM_INT);
    }
    
    protected function getTable() {
        return self::TABLE;
    }
    
    protected function buildObjectTable($result) {
        parent::buildObjectTable($result);
        $term = new Term();
        $term->setId(Arrays::get($result, self::ID));
        $term->setTermName(Arrays::get($result, self::TERM_NAME));
        $term->setTermDescription(Arrays::get($result, self::TERM_DESCRIPTION));
        $term->setTermPostCount(Arrays::get($result, self::TERM_POST_COUNT));
        
        return $term;
    }
    
}
