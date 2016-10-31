<?php
/**
 * Modulo modelo: Gestiona los datos de los post de una usuario para la plantilla de la aplicación.
 */
namespace SoftnCMS\models\theme;

use SoftnCMS\controllers\DBController;
use SoftnCMS\models\admin\base\BaseModels;
use SoftnCMS\models\admin\Post;

/**
 * Clase PostsUserTemplate para gestionar los datos de los post de una usuario para la plantilla de la aplicación.
 * @author Nicolás Marulanda P.
 */
class PostsUserTemplate extends BaseModels {
    
    /**
     * Método que obtiene los posts de una usuario.
     *
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
    
    /**
     * Método que obtiene los datos para la consulta sql.
     *
     * @param $userID
     *
     * @return array
     */
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
    
    /**
     * Método que obtiene el número de posts de un usuario.
     *
     * @param $userID
     *
     * @return int
     */
    public static function count($userID) {
        $select  = self::selectRelationships($userID);
        $prepare = [$select['prepare']];
        
        return self::countData(Post::getTableName(), $select['where'], $prepare);
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
