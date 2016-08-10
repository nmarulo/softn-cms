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
 * Description of PostInsert
 *
 * @author MaruloPC-Desk
 */
class PostInsert {

    private $postTitle;
    private $postStatus;
    private $postContents;
    private $commentStatus;
    private $userID;
    private static $COLUMNS = Post::POST_TITLE . ', ' . Post::POST_STATUS . ', ' . Post::POST_DATE . ', ' . Post::POST_UPDATE . ', ' . Post::POST_CONTENTS . ', ' . Post::COMMENT_STATUS . ', ' . Post::USER_ID;
    private static $VALUES = ':' . Post::POST_TITLE . ', ' . ':' . Post::POST_STATUS . ', ' . ':' . Post::POST_DATE . ', ' . ':' . Post::POST_UPDATE . ', ' . ':' . Post::POST_CONTENTS . ', ' . ':' . Post::COMMENT_STATUS . ', ' . ':' . Post::USER_ID;
    private $prepareStatement;

    public function __construct($postTitle, $postContents, $commentStatus, $postStatus, $userID) {
        $this->postTitle = $postTitle;
        $this->postContents = $postContents;
        $this->commentStatus = $commentStatus;
        $this->postStatus = $postStatus;
        $this->userID = $userID;
        $this->prepareStatement = [];
    }

    public function insert() {
        $db = DBController::getConnection();
        $table = Post::getTableName();
        $this->prepare();

        if (!$db->insert($table, self::$COLUMNS, self::$VALUES, $this->prepareStatement)) {
            return \FALSE;
        }
        return $db->lastInsertId();
    }

    private function prepare() {
        $date = \date('Y-m-d H:i:s', \time());
        $this->addPrepare(':' . Post::POST_TITLE, $this->postTitle, \PDO::PARAM_STR);
        $this->addPrepare(':' . Post::POST_STATUS, $this->postStatus, \PDO::PARAM_INT);
        $this->addPrepare(':' . Post::POST_DATE, $date, \PDO::PARAM_STR);
        $this->addPrepare(':' . Post::POST_UPDATE, $date, \PDO::PARAM_STR);
        $this->addPrepare(':' . Post::POST_CONTENTS, $this->postContents, \PDO::PARAM_STR);
        $this->addPrepare(':' . Post::COMMENT_STATUS, $this->commentStatus, \PDO::PARAM_INT);
        $this->addPrepare(':' . Post::USER_ID, $this->userID, \PDO::PARAM_INT);
    }

    private function addPrepare($parameter, $value, $dataType) {
        $this->prepareStatement[] = DBController::prepareStatement($parameter, $value, $dataType);
    }

}
