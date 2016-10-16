<?php
/**
 * Created by PhpStorm.
 * User: MaruloPC-Desk
 * Date: 16/09/2016
 * Time: 23:16
 */

namespace SoftnCMS\models\theme;

use SoftnCMS\controllers\DBController;
use SoftnCMS\models\admin\base\BaseModels;
use SoftnCMS\models\admin\Post;
use SoftnCMS\models\admin\PostCategory;
use SoftnCMS\models\admin\Posts;

class PostsCategoryTemplate extends BaseModels {
    
    public static function selectByCategoryIDLimit($categoryID, $limit = ''){
        $prepare = [];
        $db      = DBController::getConnection();
        
        $outPostCategory = self::selectRelationships($categoryID);
        $prepare[]       = $outPostCategory['prepare'];
        $sqlPostCategory = $db->createSelect(PostCategory::getTableName(), $outPostCategory['where'], $outPostCategory['columns'], $outPostCategory['orderBy']);
        
        $where   = Post::ID . " IN ($sqlPostCategory)";
        $select = self::select(Post::getTableName(), $where, $prepare, '*', $limit);
        
        return PostsTemplate::getInstanceData($select);
    }
    
    private static function selectRelationships($categoryID) {
        $parameter = PostCategory::RELATIONSHIPS_CATEGORY_ID;
        $where     = "$parameter = :$parameter";
        $prepare   = DBController::prepareStatement(":$parameter", $categoryID, \PDO::PARAM_INT);
        $columns   = PostCategory::RELATIONSHIPS_POST_ID;
        $orderBy   = "$columns DESC";
        
        return [
            'where'   => $where,
            'prepare' => $prepare,
            'orderBy' => $orderBy,
            'columns' => $columns,
        ];
    }
    
    public static function count($categoryID) {
        $select  = self::selectRelationships($categoryID);
        $prepare = [$select['prepare']];
        
        return self::countData(PostCategory::getTableName(), $select['where'], $prepare);
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
