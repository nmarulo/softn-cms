<?php
/**
 * Created by PhpStorm.
 * User: MaruloPC-Desk
 * Date: 17/09/2016
 * Time: 1:21
 */

namespace SoftnCMS\models\theme;

use SoftnCMS\controllers\DBController;
use SoftnCMS\models\admin\base\BaseModels;
use SoftnCMS\models\admin\Comment;
use SoftnCMS\models\admin\Comments;

class PostCommentsTemplate extends BaseModels {
    
    public static function selectByPostID($id, $limit = '') {
        
        $sqlOutput = self::selectComments($id);
        $prepare[] = $sqlOutput['prepare'];
        
        $select = self::select(Comment::getTableName(), $sqlOutput['where'], $prepare, '*', $limit, $sqlOutput['orderBy']);
        
        return Comments::getInstanceData($select);
    }
    
    private static function selectComments($id) {
        $parameter = Comment::POST_ID;
        $where     = "$parameter = :$parameter AND " . Comment::COMMENT_STATUS . " = 1";
        $prepare   = DBController::prepareStatement(":$parameter", $id, \PDO::PARAM_INT);
        $orderBy   = Comment::ID . ' DESC';
        
        return [
            'where'   => $where,
            'prepare' => $prepare,
            'orderBy' => $orderBy,
        ];
    }
    
    public static function count($id) {
        $select  = self::selectComments($id);
        $prepare = [$select['prepare']];
        
        return self::countData(Comment::getTableName(), $select['where'], $prepare);
    }
    
    private static function countData($table, $where, $prepare) {
        $columns = 'COUNT(*) AS count';
        $select  = self::select($table, $where, $prepare, $columns, '', '');
        
        if (empty($select)) {
            
            return 0;
        }
        
        return $select[0]['count'];
    }
}
