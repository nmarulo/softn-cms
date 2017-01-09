<?php

/**
 * Modulo modelo: Gestiona los datos de los post vinculados a una categoría para la plantilla de la aplicación.
 */
namespace SoftnCMS\models\theme;

use SoftnCMS\controllers\DBController;
use SoftnCMS\models\admin\base\BaseModels;
use SoftnCMS\models\admin\Post;
use SoftnCMS\models\admin\PostCategory;

/**
 * Clase PostsCategoryTemplate para gestionar los datos de los post vinculados a una categoría para la plantilla de la
 * aplicación.
 * @author Nicolás Marulanda P.
 */
class PostsCategoryTemplate extends BaseModels {
    
    /**
     * Método que obtiene los posts vinculados a una categoría.
     *
     * @param int    $categoryID Identificador.
     * @param string $limit
     *
     * @return bool|PostsTemplate Si es FALSE, no hay datos.
     */
    public static function selectByCategoryIDLimit($categoryID, $limit = '') {
        $prepare = [];
        $db      = DBController::getConnection();
        
        $outPostCategory = self::selectRelationships($categoryID);
        $prepare[]       = $outPostCategory['prepare'];
        $sqlPostCategory = $db->createSelect(PostCategory::getTableName(), $outPostCategory['where'], $outPostCategory['columns'], $outPostCategory['orderBy']);
        
        $where  = Post::ID . " IN ($sqlPostCategory)";
        $select = self::select(Post::getTableName(), $where, $prepare, '*', $limit);
        
        return PostsTemplate::getInstanceData($select);
    }
    
    /**
     * Método que obtiene los datos para la consulta sql.
     *
     * @param int $categoryID
     *
     * @return array
     */
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
    
    /**
     * Método que obtiene el número de posts vinculados a una categoría.
     *
     * @param $categoryID
     *
     * @return int
     */
    public static function count($categoryID) {
        $select  = self::selectRelationships($categoryID);
        $prepare = [$select['prepare']];
        
        return self::countData(PostCategory::getTableName(), $select['where'], $prepare);
    }
    
    /**
     * Método que obtiene el número total de datos.
     *
     * @param $table
     * @param $where
     * @param $prepare
     *
     * @return int
     */
    private static function countData($table, $where, $prepare) {
        $columns = 'COUNT(*) AS count';
        $select  = self::select($table, $where, $prepare, $columns, '', '');
        
        if (empty($select)) {
            
            return 0;
        }
        
        return $select[0]['count'];
    }
}
