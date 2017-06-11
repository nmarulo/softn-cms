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
    
    public function searchAllByPostId($postId) {
        parent::parameterQuery(self::POST_ID, $postId, \PDO::PARAM_INT);
        
        return parent::searchAllBy(self::POST_ID);
    }
    
    public function searchAllByTermId($termId) {
        parent::parameterQuery(self::TERM_ID, $termId, \PDO::PARAM_INT);
        
        return parent::searchAllBy(self::TERM_ID);
    }
    
    public function deleteAllByPostId($postId) {
        parent::parameterQuery(self::POST_ID, $postId, \PDO::PARAM_INT);
        
        return parent::deleteBy();
    }
    
    public function deleteAllByTermId($termId) {
        parent::parameterQuery(self::TERM_ID, $termId, \PDO::PARAM_INT);
        
        return parent::deleteBy();
    }
    
    public function deleteByPostAndTerm($postId, $termId) {
        parent::parameterQuery(self::POST_ID, $postId, \PDO::PARAM_INT);
        parent::parameterQuery(self::TERM_ID, $termId, \PDO::PARAM_INT);
        
        return parent::deleteBy();
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
