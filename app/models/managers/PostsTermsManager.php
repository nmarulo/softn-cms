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
    
    const POST_ID = 'post_ID';
    
    const TERM_ID = 'term_ID';
    
    public function searchAllByTermId($termId) {
        parent::parameterQuery(self::TERM_ID, $termId, \PDO::PARAM_INT);
        
        return parent::searchAllBy(self::TERM_ID);
    }
    
    public function deleteAllByPostId($postId) {
        $postsTerms = $this->searchAllByPostId($postId);
        parent::parameterQuery(self::POST_ID, $postId, \PDO::PARAM_INT);
        $result = parent::deleteBy();
        
        if ($result) {
            array_walk($postsTerms, function(PostTerm $postTerm) {
                $this->updateTermPostCount($postTerm->getTermID(), -1);
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
        
        if ($result) {
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
        
        if ($result) {
            $this->updateTermPostCount($object->getTermID(), 1);
        }
        
        return $result;
    }
    
    public function deleteByPostAndTerm($postId, $termId) {
        parent::parameterQuery(self::POST_ID, $postId, \PDO::PARAM_INT);
        parent::parameterQuery(self::TERM_ID, $termId, \PDO::PARAM_INT);
        $result = parent::deleteBy();
        
        if ($result) {
            $this->updateTermPostCount($termId, -1);
        }
        
        return $result;
    }
    
    protected function buildObjectTable($result) {
        parent::buildObjectTable($result);
        $postTerm = new PostTerm();
        $postTerm->setPostID(Arrays::get($result, self::POST_ID));
        $postTerm->setTermID(Arrays::get($result, self::TERM_ID));
        
        return $postTerm;
    }
    
    /**
     * @param PostTerm $object
     */
    protected function addParameterQuery($object) {
        parent::parameterQuery(self::TERM_ID, $object->getTermID(), \PDO::PARAM_INT);
        parent::parameterQuery(self::POST_ID, $object->getPostID(), \PDO::PARAM_INT);
    }
    
    protected function getTable() {
        return self::TABLE;
    }
    
}
