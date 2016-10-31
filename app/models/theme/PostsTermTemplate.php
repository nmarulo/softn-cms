<?php
/**
 * Modulo modelo: Gestiona los datos de los post vinculados a una etiqueta para la plantilla de la aplicación.
 */
namespace SoftnCMS\models\theme;

use SoftnCMS\controllers\DBController;
use SoftnCMS\models\admin\base\BaseModels;
use SoftnCMS\models\admin\Post;
use SoftnCMS\models\admin\PostTerm;

/**
 * Clase PostsTermTemplate para gestionar los datos de los post vinculados a una etiqueta para la plantilla de la
 * aplicación.
 * @author Nicolás Marulanda P.
 */
class PostsTermTemplate extends BaseModels {
    
    /**
     * Método que obtiene los posts vinculados a una etiqueta.
     *
     * @param int    $termID
     * @param string $limit
     *
     * @return PostsTemplate|bool Si es FALSE, no hay datos.
     */
    public static function selectByTermIDLimit($termID, $limit = '') {
        $prepare = [];
        $db      = DBController::getConnection();
        
        $outPostTerm = self::selectRelationships($termID);
        $prepare[]   = $outPostTerm['prepare'];
        $sqlPostTerm = $db->createSelect(PostTerm::getTableName(), $outPostTerm['where'], $outPostTerm['columns'], $outPostTerm['orderBy']);
        
        $where  = Post::ID . " IN ($sqlPostTerm)";
        $select = self::select(Post::getTableName(), $where, $prepare, '*', $limit);
        
        return PostsTemplate::getInstanceData($select);
    }
    
    /**
     * Método que obtiene los datos para la consulta sql.
     *
     * @param $termID
     *
     * @return array
     */
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
    
    /**
     * Método que obtiene el número de posts vinculados a una etiqueta.
     *
     * @param $termID
     *
     * @return int
     */
    public static function count($termID) {
        $select  = self::selectRelationships($termID);
        $prepare = [$select['prepare']];
        
        return self::countData(PostTerm::getTableName(), $select['where'], $prepare);
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
