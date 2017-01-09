<?php

/**
 * Modulo modelo: Gestiona grupos de Posts para la plantilla de la aplicación.
 */
namespace SoftnCMS\models\theme;

use SoftnCMS\models\admin\Post;
use SoftnCMS\models\admin\Posts;

/**
 * Clase PostsTemplate para gestionar grupos de Posts para la plantilla de la aplicación.
 * @author Nicolás Marulanda P.
 */
class PostsTemplate extends Posts {
    
    /**
     * Método que obtiene un número limitado de datos.
     *
     * @param string $limit
     *
     * @return Posts|bool Si es FALSE, no hay datos.
     */
    public static function selectByLimit($limit) {
        $select = self::select(Post::getTableName(), '', [], Post::ID, $limit);
        
        return self::getInstanceData($select);
    }
    
    /**
     * Método que recibe un lista de datos y retorna un instancia.
     *
     * @param array $data Lista de datos.
     *
     * @return PostsTemplate|bool Si es FALSE, no hay datos.
     */
    public static function getInstanceData($data) {
        return parent::getInstance($data, __CLASS__);
    }
    
    /**
     * Método que recibe una lista de datos y los agrega a la lista actual.
     *
     * @param array $data
     */
    public function addData($data) {
        foreach ($data as $value) {
            $this->add(new PostTemplate($value[Post::ID]));
        }
    }
    
}
