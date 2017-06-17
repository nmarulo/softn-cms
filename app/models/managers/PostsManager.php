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
use SoftnCMS\util\MySQL;

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
    
    const USER_ID             = 'user_ID';
    
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
        $columnCategoryId     = PostsCategoriesManager::CATEGORY_ID;
        $tablePostsCategories = parent::getTableWithPrefix(PostsCategoriesManager::TABLE);
        $query                = 'SELECT * ';
        $query                .= 'FROM ' . parent::getTableWithPrefix();
        $query                .= ' WHERE ' . self::ID . ' IN ';
        $query                .= '(SELECT ' . PostsCategoriesManager::POST_ID;
        $query                .= " FROM $tablePostsCategories ";
        $query                .= "WHERE $columnCategoryId = :$columnCategoryId) ORDER BY ID DESC ";
        $query                .= $limit === FALSE ? '' : "LIMIT $limit";
        $this->parameterQuery($columnCategoryId, $categoryId, \PDO::PARAM_INT);
        
        return parent::readData($query);
    }
    
    public function searchByTermId($termId, $filters = []) {
        $limit        = Arrays::get($filters, 'limit');
        $columnTermId = PostsTermsManager::TERM_ID;
        $query        = 'SELECT * ';
        $query        .= 'FROM ' . parent::getTableWithPrefix();
        $query        .= ' WHERE ' . self::ID . ' IN ';
        $query        .= '(SELECT ' . PostsTermsManager::POST_ID;
        $query        .= ' FROM ' . parent::getTableWithPrefix(PostsTermsManager::TABLE);
        $query        .= " WHERE $columnTermId = :$columnTermId) ORDER BY ID DESC ";
        $query        .= $limit === FALSE ? '' : "LIMIT $limit";
        $this->parameterQuery($columnTermId, $termId, \PDO::PARAM_INT);
        
        return parent::readData($query);
    }
    
    public function searchByUserId($userId, $filters = []) {
        $limit        = Arrays::get($filters, 'limit');
        $columnUserId = self::USER_ID;
        parent::parameterQuery($columnUserId, $userId, \PDO::PARAM_INT);
        
        if (empty($limit)) {
            return parent::searchAllBy($columnUserId, self::ID);
        }
        
        $query = 'SELECT * ';
        $query .= 'FROM ' . $this->getTableWithPrefix();
        $query .= " WHERE $columnUserId = :$columnUserId ";
        $query .= 'ORDER BY ID DESC ';
        $query .= "LIMIT $limit";
        
        return parent::readData($query);
    }
    
    /**
     * @param $commentId
     *
     * @return bool|Post
     */
    public function searchByCommentId($commentId) {
        $columnCommentId = CommentsManager::ID;
        $query           = 'SELECT * ';
        $query           .= 'FROM ' . parent::getTableWithPrefix();
        $query           .= ' WHERE ' . self::ID . ' IN ';
        $query           .= '(SELECT ' . CommentsManager::POST_ID;
        $query           .= ' FROM ' . parent::getTableWithPrefix(CommentsManager::TABLE);
        $query           .= " WHERE $columnCommentId = :$columnCommentId)";
        parent::parameterQuery($columnCommentId, $commentId, \PDO::PARAM_INT);
        
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
