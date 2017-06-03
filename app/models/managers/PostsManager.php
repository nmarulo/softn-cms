<?php
/**
 * PostsManager.php
 */

namespace SoftnCMS\models\managers;

use SoftnCMS\models\CRUDManagerAbstract;
use SoftnCMS\models\tables\Post;
use SoftnCMS\util\Arrays;
use SoftnCMS\util\MySQL;

/**
 * Class PostsManager
 * @author Nicolás Marulanda P.
 */
class PostsManager extends CRUDManagerAbstract {
    
    const TABLE          = 'posts';
    
    const POST_TITLE     = 'post_title';
    
    const POST_STATUS    = 'post_status';
    
    const POST_DATE      = 'post_date';
    
    const POST_UPDATE    = 'post_update';
    
    const POST_CONTENTS  = 'post_contents';
    
    const COMMENT_STATUS = 'comment_status';
    
    const COMMENT_COUNT  = 'comment_count';
    
    const USER_ID        = 'user_ID';
    
    /**
     * PostsManager constructor.
     */
    public function __construct() {
        parent::__construct();
    }
    
    public function read($filters = []) {
        if (empty($filters)) {
            return parent::readData();
        }
        
        $limit = Arrays::get($filters, 'limit');
        
        $query = 'SELECT * ';
        $query .= 'FROM ' . parent::getTableWithPrefix();
        $query .= ' ORDER BY ID DESC';
        $query .= $limit === FALSE ? '' : " LIMIT $limit";
        
        return parent::readData($query);
    }
    
    /**
     * @param Post $object
     */
    protected function addParameterQuery($object) {
        parent::parameterQuery(self::POST_TITLE, $object->getPostTitle(), \PDO::PARAM_STR);
        parent::parameterQuery(self::POST_STATUS, $object->getPostStatus(), \PDO::PARAM_INT);
        parent::parameterQuery(self::POST_DATE, $object->getPostDate(), \PDO::PARAM_STR);
        parent::parameterQuery(self::POST_UPDATE, $object->getPostUpdate(), \PDO::PARAM_STR);
        parent::parameterQuery(self::POST_CONTENTS, $object->getPostContents(), \PDO::PARAM_STR);
        parent::parameterQuery(self::COMMENT_STATUS, $object->getCommentStatus(), \PDO::PARAM_INT);
        parent::parameterQuery(self::COMMENT_COUNT, $object->getCommentCount(), \PDO::PARAM_INT);
        parent::parameterQuery(self::USER_ID, $object->getUserID(), \PDO::PARAM_INT);
    }
    
    protected function getTable() {
        return self::TABLE;
    }
    
    protected function buildObjectTable($result) {
        parent::buildObjectTable($result);
        $post = new Post();
        $post->setId(Arrays::get($result, self::ID));
        $post->setUserID(Arrays::get($result, self::USER_ID));
        $post->setCommentCount(Arrays::get($result, self::COMMENT_COUNT));
        $post->setCommentStatus(Arrays::get($result, self::COMMENT_STATUS));
        $post->setPostContents(Arrays::get($result, self::POST_CONTENTS));
        $post->setPostUpdate(Arrays::get($result, self::POST_UPDATE));
        $post->setPostDate(Arrays::get($result, self::POST_DATE));
        $post->setPostStatus(Arrays::get($result, self::POST_STATUS));
        $post->setPostTitle(Arrays::get($result, self::POST_TITLE));
        
        return $post;
    }
    
}
