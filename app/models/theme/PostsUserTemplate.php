<?php
/**
 * Created by PhpStorm.
 * User: MaruloPC-Desk
 * Date: 17/09/2016
 * Time: 0:20
 */

namespace SoftnCMS\models\theme;

use SoftnCMS\controllers\DBController;
use SoftnCMS\models\admin\base\BaseModels;
use SoftnCMS\models\admin\Post;
use SoftnCMS\models\admin\Posts;

/**
 * Class PostsUserTemplate
 * @package SoftnCMS\models\theme
 */
class PostsUserTemplate extends BaseModels {
    
    /**
     * @param int    $userID
     * @param string $limit
     *
     * @return Posts|bool Si es FALSE, no hay datos.
     */
    public static function selectByID($userID, $limit = '') {
        $prepare = [];
        $db      = DBController::getConnection();
        
        $sqlOutput = self::selectRelationships($userID);
        $prepare[] = $sqlOutput['prepare'];
        
        $select = $db->select(Post::getTableName(), 'fetchAll', $sqlOutput['where'], $prepare, '*', $sqlOutput['orderBy'], $limit);
        
        return Posts::getInstanceData($select);
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
