<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of sn-posts-categories
 *
 * @author marulo
 */
class SN_Posts_Categories {

    private $relationships_post_ID;
    private $relationships_category_ID;

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

    public static function getPosts($categoryID) {
        global $sndb;

        return $sndb->query([
                    'table' => 'posts',
                    'where' => "ID IN (SELECT relationships_post_ID FROM sn_posts_categories WHERE relationships_category_ID = $categoryID)",
                    'orderBy' => 'post_date DESC',
        ]);
    }

    public static function getCategoriesID($post_ID, $fetch = 'fetchAll') {
        global $sndb;
        
        return $sndb->query([
                    'table' => 'posts_categories',
                    'column' => 'relationships_category_ID',
                    'where' => 'relationships_post_ID = :relationships_post_ID',
                    'prepare' => [[':relationships_post_ID', $post_ID]]
                        ], $fetch);
    }

    public static function getCategories($postID) {
        global $sndb;

        return $sndb->query(array(
                    'table' => 'categories',
                    'where' => "ID IN (SELECT relationships_category_ID FROM sn_posts_categories WHERE relationships_post_ID = $postID)"
        ));
    }

}
