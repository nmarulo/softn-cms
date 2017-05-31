<?php
/**
 * CommentsManager.php
 */

namespace SoftnCMS\models\managers;

use SoftnCMS\models\CRUDManagerAbstract;
use SoftnCMS\util\MySQL;

/**
 * Class CommentsManager
 * @author Nicolás Marulanda P.
 */
class CommentsManager extends CRUDManagerAbstract {
    
    const TABLE                = 'comments';
    
    const COMMENT_STATUS       = 'comment_status';
    
    const COMMENT_AUTHOR       = 'comment_autor';
    
    const COMMENT_AUTHOR_EMAIL = 'comment_author_email';
    
    const COMMENT_DATE         = 'comment_date';
    
    const COMMENT_CONTENTS     = 'comment_contents';
    
    const COMMENT_USER_ID      = 'comment_user_ID';
    
    const POST_ID              = 'post_ID';
    
    protected function addParameterQuery($object) {
        // TODO: Implement addParameterQuery() method.
    }
    
    protected function getTable() {
        // TODO: Implement getTable() method.
    }
    
    protected function buildObjectTable($result, $fetch = MySQL::FETCH_ALL) {
        // TODO: Implement buildObjectTable() method.
    }
    
}
