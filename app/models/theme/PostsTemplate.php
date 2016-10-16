<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SoftnCMS\models\theme;

use SoftnCMS\models\admin\Post;
use SoftnCMS\models\admin\Posts;

/**
 * Class PostsTemplate
 * @author Nicolás Marulanda P.
 */
class PostsTemplate extends Posts {
    
    /**
     * Metodo que obtiene un número limitado de datos.
     * @param string $limit
     * @return Posts|bool Si es FALSE, no hay datos.
     */
    public static function selectByLimit($limit) {
        $select = self::select(Post::getTableName(), '', [], Post::ID, $limit);
        
        return self::getInstanceData($select);
    }
    
    /**
     * Metodo que recibe un lista de datos y retorna un instancia.
     * @param array $data Lista de datos.
     * @return Posts|bool Si es FALSE, no hay datos.
     */
    public static function getInstanceData($data) {
        return parent::getInstance($data, __CLASS__);
    }
    
    public function addData($data) {
        foreach ($data as $value) {
            $this->add(new PostTemplate($value[Post::ID]));
        }
    }
    
}
