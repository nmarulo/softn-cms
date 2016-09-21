<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SoftnCMS\models\theme;

use SoftnCMS\models\admin\Post;
use SoftnCMS\models\admin\Posts;
use SoftnCMS\models\admin\base\BaseModels;

/**
 * Description of PostsTemplate
 * @author Nicolás Marulanda P.
 */
class PostsListTemplate extends BaseModels {
    
    /**
     * Metodo que obtiene todos los posts publicados, es decir, que su estado sea igual a 1.
     * @param string $limit
     * @return Posts|bool Si es FALSE, no hay datos.
     */
    public static function selectAll($limit = '') {
        $select = self::selectAllData();
        $output = self::select(Post::getTableName(), $select['where'], [], '*', $limit);
        
        return Posts::getInstanceData($output);
    }
    
    private static function selectAllData() {
        //Se descartan los post no publicados.
        $where = Post::POST_STATUS . ' = 1';
        
        return [
            'where' => $where,
        ];
    }
    
    /**
     * Metodo que obtiene el número total de datos.
     * @return int
     */
    public static function count() {
        $select = self::selectAllData();
        
        return self::countData(Post::getTableName(), $select['where'], []);
    }
    
    public static function countData($table, $where, $prepare) {
        $columns = 'COUNT(*) AS count';
        $select  = self::select($table, $where, $prepare, $columns, '', '');
        
        if (empty($select)) {
            
            return 0;
        }
        
        return $select[0]['count'];
    }
    
}
