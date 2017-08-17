<?php
/**
 * PostsManager.php
 */

namespace SoftnCMS\models\managers;

use SoftnCMS\models\CRUDManagerAbstract;
use SoftnCMS\models\tables\Post;
use SoftnCMS\models\tables\PostCategory;
use SoftnCMS\models\tables\PostTerm;
use SoftnCMS\util\Arrays;

/**
 * Class PostsManager
 * @author Nicolás Marulanda P.
 */
class PostsManager extends CRUDManagerAbstract {
    
    const TABLE               = 'posts';
    
    const POST_TITLE          = 'post_title';
    
    const POST_STATUS         = 'post_status';
    
    const POST_DATE           = 'post_date';
    
    const POST_UPDATE         = 'post_update';
    
    const POST_CONTENTS       = 'post_contents';
    
    const POST_COMMENT_STATUS = 'post_comment_status';
    
    const POST_COMMENT_COUNT  = 'post_comment_count';
    
    const USER_ID             = 'user_id';
    
    /**
     * PostsManager constructor.
     */
    public function __construct() {
        parent::__construct();
    }
    
    public function delete($id) {
        $usersManager           = new UsersManager();
        $postsCategoriesManager = new PostsCategoriesManager();
        $postsTermsManager      = new PostsTermsManager();
        $postsCategories        = $postsCategoriesManager->searchAllByPostId($id);
        $postsTerms             = $postsTermsManager->searchAllByPostId($id);
        $user                   = $usersManager->searchByPostId($id);
        $postsCategoriesManager->deleteAllByPostId($id);
        $postsTermsManager->deleteAllByPostId($id);
        $result = parent::delete($id);
        
        if ($result) {
            $usersManager->updatePostCount($user->getId(), -1);
        } else {
            //En caso de fallar el borrado del post, vuelvo a crear la relaciones.
            array_walk($postsCategories, function(PostCategory $postCategory) use ($postsCategoriesManager) {
                $postsCategoriesManager->create($postCategory);
            });
            array_walk($postsTerms, function(PostTerm $postTerm) use ($postsTermsManager) {
                $postsTermsManager->create($postTerm);
            });
        }
        
        return $result;
    }
    
    public function searchByCategoryId($categoryId, $filters = []) {
        $limit                = Arrays::get($filters, 'limit');
        $tablePostsCategories = parent::getTableWithPrefix(PostsCategoriesManager::TABLE);
        $query                = 'SELECT * FROM %1$s WHERE %2$s IN (SELECT %3$s FROM %4$s WHERE %5$s = :%5$s) ORDER BY %2$s DESC';
        $query                = sprintf($query, parent::getTableWithPrefix(), self::ID, PostsCategoriesManager::POST_ID, $tablePostsCategories, PostsCategoriesManager::CATEGORY_ID);
        $query                .= $limit === FALSE ? '' : " LIMIT $limit";
        $this->parameterQuery(PostsCategoriesManager::CATEGORY_ID, $categoryId, \PDO::PARAM_INT);
        
        return parent::readData($query);
    }
    
    public function searchByTermId($termId, $filters = []) {
        $limit           = Arrays::get($filters, 'limit');
        $tablePostsTerms = parent::getTableWithPrefix(PostsTermsManager::TABLE);
        $query           = 'SELECT * FROM %1$s WHERE %2$s IN (SELECT %3$s FROM %4$s WHERE %5$s = :%5$s) ORDER BY %2$s DESC';
        $query           = sprintf($query, parent::getTableWithPrefix(), self::ID, PostsTermsManager::POST_ID, $tablePostsTerms, PostsTermsManager::TERM_ID);
        $query           .= $limit === FALSE ? '' : "LIMIT $limit";
        $this->parameterQuery(PostsTermsManager::TERM_ID, $termId, \PDO::PARAM_INT);
        
        return parent::readData($query);
    }
    
    public function searchByUserId($userId, $filters = []) {
        $limit = Arrays::get($filters, 'limit');
        parent::parameterQuery(self::USER_ID, $userId, \PDO::PARAM_INT);
        
        if (empty($limit)) {
            return parent::searchAllBy(self::USER_ID, self::ID);
        }
        
        $query = 'SELECT * FROM %1$s WHERE %2$s = :%2$s ORDER BY %3$s DESC LIMIT %4$s';
        $query = sprintf($query, parent::getTableWithPrefix(), self::USER_ID, self::ID, $limit);
        
        return parent::readData($query);
    }
    
    /**
     * @param $commentId
     *
     * @return bool|Post
     */
    public function searchByCommentId($commentId) {
        $tableComments = parent::getTableWithPrefix(CommentsManager::TABLE);
        $query         = 'SELECT * FROM %1$s WHERE %2$s IN (SELECT %3$s FROM %4$s WHERE %5$s = :%5$s)';
        $query         = sprintf($query, parent::getTableWithPrefix(), self::ID, CommentsManager::POST_ID, $tableComments, CommentsManager::ID);
        parent::parameterQuery(CommentsManager::ID, $commentId, \PDO::PARAM_INT);
        
        return Arrays::get(parent::readData($query), 0);
    }
    
    public function updateCommentCount($postId, $num) {
        $post = $this->searchById($postId);
        $post->setPostCommentCount($post->getPostCommentCount() + $num);
        
        return parent::update($post);
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
        parent::parameterQuery(self::POST_COMMENT_STATUS, $object->getPostCommentStatus(), \PDO::PARAM_INT);
        parent::parameterQuery(self::POST_COMMENT_COUNT, $object->getPostCommentCount(), \PDO::PARAM_INT);
        parent::parameterQuery(self::USER_ID, $object->getUserId(), \PDO::PARAM_INT);
    }
    
    protected function getTable() {
        return self::TABLE;
    }
    
    protected function buildObjectTable($result) {
        parent::buildObjectTable($result);
        $post = new Post();
        $post->setId(Arrays::get($result, self::ID));
        $post->setUserId(Arrays::get($result, self::USER_ID));
        $post->setPostCommentCount(Arrays::get($result, self::POST_COMMENT_COUNT));
        $post->setPostCommentStatus(Arrays::get($result, self::POST_COMMENT_STATUS));
        $post->setPostContents(Arrays::get($result, self::POST_CONTENTS));
        $post->setPostUpdate(Arrays::get($result, self::POST_UPDATE));
        $post->setPostDate(Arrays::get($result, self::POST_DATE));
        $post->setPostStatus(Arrays::get($result, self::POST_STATUS));
        $post->setPostTitle(Arrays::get($result, self::POST_TITLE));
        
        return $post;
    }
    
}
