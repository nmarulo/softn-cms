<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of sn-posts-terms
 *
 * @author marulo
 */
class SN_Posts_Terms {

    private $relationships_post_ID;
    private $relationships_term_ID;

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

    public static function delete($postID, $termID) {
        global $sndb;

        $where = "relationships_post_ID = :relationships_post_ID";
        $prepare = [[':relationships_post_ID', $postID]];

        if (!empty($termID)) {
            $where .= " AND relationships_term_ID = :relationships_term_ID";
            $prepare[] = [':relationships_term_ID', $termID];
        }

        return $sndb->exec(array(
                    'type' => 'DELETE',
                    'table' => 'posts_terms',
                    'where' => $where,
                    'prepare' => $prepare
        ));
    }

    public static function getPosts($termID) {
        global $sndb;

        return $sndb->query([
                    'table' => 'posts',
                    'where' => "ID IN (SELECT relationships_post_ID FROM sn_posts_terms WHERE relationships_term_ID = $termID)",
                    'orderBy' => 'post_date DESC',
        ]);
    }

    public static function getTermsID($post_ID, $fetch = 'fetchAll') {
        global $sndb;
        
        return $sndb->query([
                    'table' => 'posts_terms',
                    'column' => 'relationships_term_ID',
                    'where' => 'relationships_post_ID = :relationships_post_ID',
                    'prepare' => [[':relationships_post_ID', $post_ID]]
                        ], $fetch);
    }

    public static function getTerms($postID) {
        global $sndb;

        return $sndb->query(array(
                    'table' => 'terms',
                    'where' => "ID IN (SELECT relationships_term_ID FROM sn_posts_terms WHERE relationships_post_ID = $postID)"
        ));
    }

}
