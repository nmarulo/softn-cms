<?php

/**
 * Gestión de las relaciones de entradas con etiquetas.
 * @package SoftN-CMS\sn-includes
 */

/**
 * Clase que estableces funciones de las relaciones de entradas con etiquetas.
 * @author Nicolás Marulanda P.
 */
class SN_Posts_Terms {

    /**
     * Metodo que agrega los datos a la tabla de las relaciones de entradas con etiquetas.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @param int $postID Identificador de la entrada.
     * @param int $termID Identificador de la etiqueta.
     * @return bool
     */
    public static function insert($postID, $termID) {
        global $sndb;

        return $sndb->exec([
                    'type' => 'INSERT',
                    'table' => 'posts_terms',
                    'values' => ':postID , :termID',
                    'prepare' => [
                        [':postID', $postID],
                        [':termID', $termID],
                    ],
        ]);
    }

    /**
     * Metodo que borra las relaciones de entradas con etiquetas.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @param int $postID Identificador de la entrada.
     * @param int $termID Identificador de la etiqueta.
     * @return bool
     */
    public static function delete($postID, $termID) {
        global $sndb;

        $where = 'relationships_post_ID = :relationships_post_ID';
        $prepare = [[':relationships_post_ID', $postID]];

        if (!empty($termID)) {
            $where .= ' AND relationships_term_ID = :relationships_term_ID';
            $prepare[] = [':relationships_term_ID', $termID];
        }

        return $sndb->exec(array(
                    'type' => 'DELETE',
                    'table' => 'posts_terms',
                    'where' => $where,
                    'prepare' => $prepare
        ));
    }

    /**
     * Metodo que obtiene todas las publicaciones relacionadas con una etiqueta.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @param int $termID Identificador de la etiqueta.
     * @return object Retorna un objecto PDOstatement
     */
    public static function getPosts($termID) {
        global $sndb;

        return $sndb->query([
                    'table' => 'posts',
                    'where' => 'ID IN (SELECT relationships_post_ID FROM ' . DB_PREFIX . 'posts_terms WHERE relationships_term_ID = :relationships_term_ID)',
                    'prepare' => [[':relationships_term_ID', $termID],],
                    'orderBy' => 'post_date DESC',
        ]);
    }

    /**
     * Metodo que obtiene el ID de todas las etiquetas relacionadas con una entrada.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @param int $postID Identificador de la entrada.
     * @param string $fetch [Opcional] Tipo de datos a retornar.
     * Con "fetchObject" para retornar los datos como objetos. 
     * Por defecto, "fetchAll", retorna un array asociativo.
     * @return array|object
     */
    public static function getTermsID($postID, $fetch = 'fetchAll') {
        global $sndb;

        return $sndb->query([
                    'table' => 'posts_terms',
                    'column' => 'relationships_term_ID',
                    'where' => 'relationships_post_ID = :relationships_post_ID',
                    'prepare' => [[':relationships_post_ID', $postID]]
                        ], $fetch);
    }

    /**
     * Metodo que obtiene todas las etiquetas relacionadas con una entrada.
     * @global SN_DB $sndb Conexión de la base de datos.
     * @param type $postID Identificador de la etiqueta.
     * @return object Retorna un objecto PDOstatement.
     */
    public static function getTerms($postID) {
        global $sndb;

        return $sndb->query([
                    'table' => 'terms',
                    'where' => 'ID IN (SELECT relationships_term_ID FROM ' . DB_PREFIX . 'posts_terms WHERE relationships_post_ID = :relationships_post_ID)',
                    'prepare' => [[':relationships_post_ID', $postID]],
                    'orderBy' => 'term_name'
        ]);
    }

}
