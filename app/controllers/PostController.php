<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SoftnCMS\controllers;

use SoftnCMS\models\Posts;
use SoftnCMS\models\PostUpdate;
use SoftnCMS\models\PostInsert;
use SoftnCMS\models\PostDelete;

/**
 * Description of PostController
 *
 * @author Nicolás Marulanda P.
 */
class PostController {

    public function index() {
        return ['data' => $this->dataIndex()];
    }

    public function update($id) {
        return ['data' => $this->dataUpdate($id)];
    }

    public function delete($id) {
        return ['data' => $this->dataDelete($id)];
    }

    public function insert() {
        return ['data' => $this->dataInsert()];
    }

    private function dataIndex() {
        $posts = new Posts();
        $output = $posts->getPosts();

        foreach ($output as $post) {
            $title = $post->getPostTitle();

            if (isset($title{30})) {
                $title = substr($title, 0, 30) . ' [...]';
            }
            $post->setPostTitle($title);
        }

        return ['posts' => $output];
    }

    private function dataInsert() {
        if (filter_input(\INPUT_POST, 'publish')) {
            $dataInput = $this->getDataInput();
            $insert = new PostInsert($dataInput['postTitle'], $dataInput['postContents'], $dataInput['commentStatus'], $dataInput['postStatus'], 1);
            header('Location: ' . \LOCALHOST . 'admin/post/update/' . $insert->insert());
            exit();
        }

        return [
            'post' => Post::defaultInstance(),
            'actionUpdate' => \FALSE
        ];
    }

    private function dataUpdate($id) {
        if (empty($id)) {
            header('Location: ' . \LOCALHOST . 'admin/post');
            exit();
        }

        $posts = new Posts();
        $post = $posts->getPost($id);

        if (filter_input(\INPUT_POST, 'update')) {
            $dataInput = $this->getDataInput();
            $update = new PostUpdate($post, $dataInput['postTitle'], $dataInput['postContents'], $dataInput['commentStatus'], $dataInput['postStatus']);
            $post = $update->update();
        }

        return [
            'post' => $post,
            'actionUpdate' => \TRUE
        ];
    }

    private function dataDelete($id) {
        $delete = new PostDelete($id);
        $delete->delete();
        return $this->dataIndex();
    }

    private function getDataInput() {
        return [
            'postTitle' => \filter_input(\INPUT_POST, 'postTitle'),
            'postContents' => \filter_input(\INPUT_POST, 'postContents'),
            'commentStatus' => \filter_input(\INPUT_POST, 'commentStatus'),
            'postStatus' => \filter_input(\INPUT_POST, 'postStatus'),
//            'relationshipsCategoryID' => \filter_input(\INPUT_POST, 'relationshipsCategoryID', \FILTER_DEFAULT, \FILTER_REQUIRE_ARRAY),
//            'relationshipsTermID' => \filter_input(\INPUT_POST, 'relationshipsTermID', \FILTER_DEFAULT, \FILTER_REQUIRE_ARRAYRAY),
        ];
    }

}
