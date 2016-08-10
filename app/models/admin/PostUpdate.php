<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\models\admin\Post;
use SoftnCMS\controllers\DBController;

/**
 * Description of PostUpdate
 *
 * @author Nicolás Marulanda P.
 */
class PostUpdate {

    /**
     *
     * @var Post 
     */
    private $post;
    private $postTitle;
    private $postContents;
    private $commentStatus;
    private $postStatus;
    private $dataColumns;
    private $prepareStatement;

    public function __construct(Post $post, $postTitle, $postContents, $commentStatus, $postStatus) {
        $this->post = $post;
        $this->postTitle = $postTitle;
        $this->postContents = $postContents;
        $this->commentStatus = $commentStatus;
        $this->postStatus = $postStatus;
        $this->prepareStatement = [];
        $this->dataColumns = "";
    }

    public function update() {
        $db = DBController::getConnection();
        $table = Post::getTableName();
        $columns = '*';
        $where = 'ID = :id';
        $fetch = 'fetchAll';

        $this->prepare();

        $parameter = ':id';
        $newData = $this->post->getID();
        $dataType = \PDO::PARAM_INT;
        $this->prepareStatement[] = DBController::prepareStatement($parameter, $newData, $dataType);

        if (!$db->update($table, $this->dataColumns, $where, $this->prepareStatement)) {
            return \FALSE;
        }

        $count = \count($this->prepareStatement) - 1;
        $prepare = [$this->prepareStatement[$count]];
        $select = $db->select($table, $fetch, $where, $prepare, $columns);
        $post = new Post($select[0]);
        return $post;
    }

    private function prepare() {
        $parameter = '';
        $postUpdate = \date('Y-m-d H:i:s', \time());

        $this->checkFields($this->post->getPostTitle(), $this->postTitle, Post::POST_TITLE, \PDO::PARAM_STR);
        $this->checkFields($this->post->getPostContents(), $this->postContents, Post::POST_CONTENTS, \PDO::PARAM_STR);
        $this->checkFields($this->post->getCommentStatus(), $this->commentStatus, Post::COMMENT_STATUS, \PDO::PARAM_STR);
        $this->checkFields($this->post->getPostStatus(), $this->postStatus, Post::POST_STATUS, \PDO::PARAM_INT);

        $parameter = ':' . Post::POST_UPDATE;
        $this->addSetDataSQL(Post::POST_UPDATE, $parameter);
        $this->prepareStatement[] = DBController::prepareStatement($parameter, $postUpdate, \PDO::PARAM_STR);
    }

    private function checkFields($oldData, $newData, $column, $dataType) {
        if ($oldData != $newData) {
            $parameter = ':' . $column;
            $this->addSetDataSQL($column, $parameter);
            $this->prepareStatement[] = DBController::prepareStatement($parameter, $newData, $dataType);
        }
    }

    private function addSetDataSQL($key, $data) {
        $this->dataColumns .= empty($this->dataColumns) ? '' : ', ';
        $this->dataColumns .= "$key = $data";
    }

}
