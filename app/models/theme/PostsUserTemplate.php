<?php
/**
 *
 */

namespace SoftnCMS\models\theme;

use SoftnCMS\controllers\DBController;
use SoftnCMS\models\admin\base\BaseModels;
use SoftnCMS\models\admin\Post;
use SoftnCMS\models\admin\Posts;

/**
 * Class PostsUserTemplate
 * @author Nicolás Marulanda P.
 */
class PostsUserTemplate extends BaseModels {
    
    /**
     * @param int    $userID
     * @param string $limit
     *
     * @return PostsTemplate|bool Si es FALSE, no hay datos.
     */
    public static function selectByUserIDLimit($userID, $limit = '') {
        $prepare   = [];
        $sqlOutput = self::selectRelationships($userID);
        $prepare[] = $sqlOutput['prepare'];
        
        $select = self::select(Post::getTableName(), $sqlOutput['where'], $prepare, '*', $limit);
        
        return PostsTemplate::getInstanceData($select);
    }
    
    private static function selectRelationships($userID) {
        $parameter = Post::USER_ID;
        $where     = "$parameter = :$parameter";
        $prepare   = DBController::prepareStatement(":$parameter", $userID, \PDO::PARAM_INT);
        $orderBy   = Post::ID . ' DESC';
        
        return [
            'where'   => $where,
            'prepare' => $prepare,
            'orderBy' => $orderBy,
        ];
    }
    
    public static function count($userID) {
        $select  = self::selectRelationships($userID);
        $prepare = [$select['prepare']];
        
        return self::countData(Post::getTableName(), $select['where'], $prepare);
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
