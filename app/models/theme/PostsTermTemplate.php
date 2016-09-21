<?php
/**
 *
 */

namespace SoftnCMS\models\theme;

use SoftnCMS\controllers\DBController;
use SoftnCMS\models\admin\base\BaseModels;
use SoftnCMS\models\admin\Post;
use SoftnCMS\models\admin\Posts;
use SoftnCMS\models\admin\PostTerm;

class PostsTermTemplate extends BaseModels {
    
    /**
     * @param int    $termID
     * @param string $limit
     *
     * @return Posts|bool Si es FALSE, no hay datos.
     */
    public static function selectByID($termID, $limit = '') {
        $prepare = [];
        $db      = DBController::getConnection();
        
        $outPostTerm = self::selectRelationships($termID);
        $prepare[]   = $outPostTerm['prepare'];
        $sqlPostTerm = $db->createSelect(PostTerm::getTableName(), $outPostTerm['where'], $outPostTerm['columns'], $outPostTerm['orderBy']);
        
        $where   = Post::ID . " IN ($sqlPostTerm)";
        $orderBy = Post::ID . ' DESC';
        $select  = $db->select(Post::getTableName(), 'fetchAll', $where, $prepare, '*', $orderBy, $limit);
        
        return Posts::getInstanceData($select);
    }
    
    private static function selectRelationships($termID) {
        $parameter = PostTerm::RELATIONSHIPS_TERM_ID;
        $where     = "$parameter = :$parameter";
        $prepare   = DBController::prepareStatement(":$parameter", $termID, \PDO::PARAM_INT);
        $columns   = PostTerm::RELATIONSHIPS_POST_ID;
        $orderBy   = "$columns DESC";
        
        return [
            'where'   => $where,
            'prepare' => $prepare,
            'orderBy' => $orderBy,
            'columns' => $columns,
        ];
    }
    
    public static function count($termID) {
        $select  = self::selectRelationships($termID);
        $prepare = [$select['prepare']];
        
        return self::countData(PostTerm::getTableName(), $select['where'], $prepare);
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
