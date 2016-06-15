<?php

/**
 * Gestión de las relaciones de entradas con categorias.
 * @package SoftN-CMS\sn-includes
 */

/**
 * Clase que estableces funciones de las relaciones de entradas con categorias.
 * @author Nicolás Marulanda P.
 */
class SN_Posts_Categories {

    /**
     * Metodo que agrega los datos a la tabla de las relaciones de entradas con categorias.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @param int $postID Identificador de la entrada.
     * @param int $categoryID Identificador de la categoría.
     * @return bool
     */
    public static function insert($postID, $categoryID) {
        global $sndb;

        return $sndb->exec([
                    'type' => 'INSERT',
                    'table' => 'posts_categories',
                    'values' => ':postID , :categoryID',
                    'prepare' => [
                        [':postID', $postID],
                        [':categoryID', $categoryID]
                    ],
        ]);
    }

    /**
     * Metodo que borra las relaciones de entradas con categorias.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @param int $postID Identificador de la entrada.
     * @param int $categoryID Identificador de la entrada.
     * @return bool
     */
    public static function delete($postID, $categoryID = 0) {
        global $sndb;

        $where = "relationships_post_ID = :relationships_post_ID";
        $prepare = [[':relationships_post_ID', $postID]];

        if ($categoryID) {
            $where .= " AND relationships_category_ID = :relationships_category_ID";
            $prepare[] = ['relationships_category_ID', $categoryID];
        }

        return $sndb->exec([
                    'type' => 'DELETE',
                    'table' => 'posts_categories',
                    'where' => $where,
                    'prepare' => $prepare,
        ]);
    }

    /**
     * Metodo que obtiene todas las publicaciones relacionadas con una categoría.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @param int $categoryID Identificador de la categoría.
     * @return object Retorna un objecto PDOstatement
     */
    public static function getPosts($categoryID) {
        global $sndb;

        return $sndb->query([
                    'table' => 'posts',
                    'where' => "ID IN (SELECT relationships_post_ID FROM sn_posts_categories WHERE relationships_category_ID = $categoryID)",
                    'orderBy' => 'post_date DESC',
        ]);
    }

    /**
     * Metodo que obtiene el ID de todas las categorías relacionadas con una entrada.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @param int $postID Identificador de la entrada.
     * @param string $fetch [Opcional] Tipo de datos a retornar.
     * Con "fetchObject" para retornar los datos como objetos. 
     * Por defecto, "fetchAll", retorna un array asociativo.
     * @return array|object
     */
    public static function getCategoriesID($postID, $fetch = 'fetchAll') {
        global $sndb;

        return $sndb->query([
                    'table' => 'posts_categories',
                    'column' => 'relationships_category_ID',
                    'where' => 'relationships_post_ID = :relationships_post_ID',
                    'prepare' => [[':relationships_post_ID', $postID]]
                        ], $fetch);
    }

    /**
     * Metodo que obtiene todas las categorías relacionadas con una entrada.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @param int $postID Identificador de la entrada.
     * @return object Retorna un objecto PDOstatement.
     */
    public static function getCategories($postID) {
        global $sndb;

        return $sndb->query(array(
                    'table' => 'categories',
                    'where' => "ID IN (SELECT relationships_category_ID FROM sn_posts_categories WHERE relationships_post_ID = $postID)"
        ));
    }

}
