<?php
/**
 * PostsTermsManager.php
 */

namespace SoftnCMS\models\managers;

use SoftnCMS\models\CRUDManagerAbstract;
use SoftnCMS\models\tables\PostTerm;
use SoftnCMS\util\Arrays;

/**
 * Class PostsTermsManager
 * @author Nicolás Marulanda P.
 */
class PostsTermsManager extends CRUDManagerAbstract {
    
    const TABLE   = 'posts_terms';
    
    const POST_ID = 'post_id';
    
    const TERM_ID = 'term_id';
    
    public function countPostsByTermIdAndPostStatus($termId, $postStatus) {
        $tablePosts = $this->getTableWithPrefix(PostsManager::TABLE);
        $table      = $this->getTableWithPrefix();
        $query      = sprintf('SELECT COUNT(*) AS COUNT FROM %1$s WHERE %2$s = :%2$s AND %3$s IN (SELECT %4$s FROM %5$s WHERE %6$s = :%6$s)', $table, self::TERM_ID, self::POST_ID, PostsManager::ID, $tablePosts, PostsManager::POST_STATUS);
        parent::parameterQuery(self::TERM_ID, $termId, \PDO::PARAM_INT);
        parent::parameterQuery(PostsManager::POST_STATUS, $postStatus, \PDO::PARAM_INT);
        $result = $this->select($query);
        $result = Arrays::get($result, 0);
        $result = Arrays::get($result, 'COUNT');
        
        return $result === FALSE ? 0 : $result;
    }
    
    public function searchAllByTermId($termId) {
        parent::parameterQuery(self::TERM_ID, $termId, \PDO::PARAM_INT);
        
        return parent::searchAllBy(self::TERM_ID);
    }
    
    public function deleteAllByPostId($postId) {
        $postsTerms = $this->searchAllByPostId($postId);
        parent::parameterQuery(self::POST_ID, $postId, \PDO::PARAM_INT);
        $result = parent::deleteBy();
        
        if (!empty($result)) {
            array_walk($postsTerms, function(PostTerm $postTerm) {
                $this->updateTermPostCount($postTerm->getTermId(), -1);
            });
        }
        
        return $result;
    }
    
    public function searchAllByPostId($postId) {
        parent::parameterQuery(self::POST_ID, $postId, \PDO::PARAM_INT);
        
        return parent::searchAllBy(self::POST_ID);
    }
    
    private function updateTermPostCount($termId, $num) {
        $termsManager = new TermsManager();
        
        return $termsManager->updatePostCount($termId, $num);
    }
    
    public function deleteAllByTermId($termId) {
        parent::parameterQuery(self::TERM_ID, $termId, \PDO::PARAM_INT);
        $result = parent::deleteBy();
        
        if (!empty($result)) {
            $this->updateTermPostCount($termId, -1);
        }
        
        return $result;
    }
    
    /**
     * @param PostTerm $object
     *
     * @return bool
     */
    public function create($object) {
        $result = parent::create($object);
        
        if (!empty($result)) {
            $this->updateTermPostCount($object->getTermId(), 1);
        }
        
        return $result;
    }
    
    public function deleteByPostAndTerm($postId, $termId) {
        parent::parameterQuery(self::POST_ID, $postId, \PDO::PARAM_INT);
        parent::parameterQuery(self::TERM_ID, $termId, \PDO::PARAM_INT);
        $result = parent::deleteBy();
        
        if (!empty($result)) {
            $this->updateTermPostCount($termId, -1);
        }
        
        return $result;
    }
    
    protected function buildObjectTable($result) {
        parent::buildObjectTable($result);
        $postTerm = new PostTerm();
        $postTerm->setPostId(Arrays::get($result, self::POST_ID));
        $postTerm->setTermId(Arrays::get($result, self::TERM_ID));
        
        return $postTerm;
    }
    
    /**
     * @param PostTerm $object
     */
    protected function addParameterQuery($object) {
        parent::parameterQuery(self::TERM_ID, $object->getTermId(), \PDO::PARAM_INT);
        parent::parameterQuery(self::POST_ID, $object->getPostId(), \PDO::PARAM_INT);
    }
    
    protected function getTable() {
        return self::TABLE;
    }
    
}
