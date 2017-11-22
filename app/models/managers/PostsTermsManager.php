<?php
/**
 * PostsTermsManager.php
 */

namespace SoftnCMS\models\managers;

use SoftnCMS\models\tables\PostTerm;
use SoftnCMS\util\Arrays;
use SoftnCMS\util\database\ManagerAbstract;

/**
 * Class PostsTermsManager
 * @author Nicolás Marulanda P.
 */
class PostsTermsManager extends ManagerAbstract {
    
    const TABLE   = 'posts_terms';
    
    const POST_ID = 'post_id';
    
    const TERM_ID = 'term_id';
    
    public function countPostsByTermIdAndPostStatus($termId, $postStatus) {
        $tablePosts = $this->getTableWithPrefix(PostsManager::TABLE);
        $table      = $this->getTableWithPrefix();
        $query      = sprintf('SELECT COUNT(*) AS COUNT FROM %1$s WHERE %2$s = :%2$s AND %3$s IN (SELECT %4$s FROM %5$s WHERE %6$s = :%6$s)', $table, self::TERM_ID, self::POST_ID, PostsManager::COLUMN_ID, $tablePosts, PostsManager::POST_STATUS);
        parent::addPrepareStatement(self::TERM_ID, $termId, \PDO::PARAM_INT);
        parent::addPrepareStatement(PostsManager::POST_STATUS, $postStatus, \PDO::PARAM_INT);
        $result = Arrays::findFirst(parent::getConnection()
                                          ->select($query));
        
        return empty($result) ? 0 : $result;
    }
    
    public function searchAllByTermId($termId) {
        return parent::searchAllByColumn($termId, self::TERM_ID, \PDO::PARAM_INT);
    }
    
    public function deleteAllByPostId($postId) {
        $postsTerms = $this->searchAllByPostId($postId);
        $result     = parent::deleteByColumn($postId, self::POST_ID, \PDO::PARAM_INT);
        
        if (!empty($result)) {
            array_walk($postsTerms, function(PostTerm $postTerm) {
                $this->updateTermPostCount($postTerm->getTermId(), -1);
            });
        }
        
        return $result;
    }
    
    public function searchAllByPostId($postId) {
        return parent::searchAllByColumn($postId, self::POST_ID, \PDO::PARAM_INT);
    }
    
    private function updateTermPostCount($termId, $num) {
        $termsManager = new TermsManager();
        
        return $termsManager->updatePostCount($termId, $num);
    }
    
    public function deleteAllByTermId($termId) {
        $result = parent::deleteByColumn($termId, self::TERM_ID, \PDO::PARAM_INT);
        
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
        //"Create" retorna el id, pero, "posts_terms" no tiene "id" y retorna "0".
        $result = parent::create($object);
        
        if (!empty($result) || $result == 0) {
            $this->updateTermPostCount($object->getTermId(), 1);
        }
        
        return $result;
    }
    
    public function deleteByPostAndTerm($postId, $termId) {
        parent::addPrepareStatement(self::POST_ID, $postId, \PDO::PARAM_INT);
        parent::addPrepareStatement(self::TERM_ID, $termId, \PDO::PARAM_INT);
        $result = parent::deleteByPrepareStatement();
        
        if (!empty($result)) {
            $this->updateTermPostCount($termId, -1);
        }
        
        return $result;
    }
    
    protected function buildObject($result) {
        $postTerm = new PostTerm();
        $postTerm->setPostId(Arrays::get($result, self::POST_ID));
        $postTerm->setTermId(Arrays::get($result, self::TERM_ID));
        
        return $postTerm;
    }
    
    /**
     * @param PostTerm $object
     */
    protected function prepareStatement($object) {
        parent::addPrepareStatement(self::TERM_ID, $object->getTermId(), \PDO::PARAM_INT);
        parent::addPrepareStatement(self::POST_ID, $object->getPostId(), \PDO::PARAM_INT);
    }
    
    protected function getTable() {
        return self::TABLE;
    }
    
}
