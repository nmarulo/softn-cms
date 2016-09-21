<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SoftnCMS\models\theme;

use SoftnCMS\models\admin\Comment;

/**
 * Description of CommentTemplate
 *
 * @author Nicolás Marulanda P.
 */
class CommentTemplate {

    /** @var Comment Instancia. */
    private $comment;

    public function __construct($comment) {
        $this->comment = $comment;
    }

    public function getID() {
        return $this->comment->getID();
    }

    public function getInstance() {
        return $this->comment;
    }

    public function getCommentID($isEcho = \TRUE, $addID = 'comment-') {
        if (!$isEcho) {

            return $addID . $this->getID();
        }

        echo $addID . $this->getID();
    }

    public function getCommentStatus() {
        return $this->comment->getCommentStatus();
    }

    public function getCommentUrl($isEcho = \TRUE) {
        global $urlSite;

        if (!$isEcho) {

            return $urlSite . 'post/' . $this->comment->getPostID() . '/#' . $this->getID();
        }

        echo $urlSite . 'post/' . $this->comment->getPostID() . '/#' . $this->getID();
    }

    public function getCommentAvatar($isEcho = \TRUE) {
        if (!$isEcho) {

            return "";
        }

        echo "";
    }

    public function getCommentUrlAuthor($isEcho = \TRUE) {
        global $urlSite;

        if (!$isEcho) {

            return $urlSite . 'user/' . $this->comment->getCommentUserID();
        }

        echo $urlSite . 'user/' . $this->comment->getCommentUserID();
    }
    
    public function isCommentUrlAuthor(){
        return $this->comment->getCommentUserID() > 0;
    }

    public function getCommentAuthor($isEcho = \TRUE) {
        if (!$isEcho) {

            return $this->comment->getCommentAutor();
        }

        echo $this->comment->getCommentAutor();
    }

    public function getCommentAuthorEmail($isEcho = \TRUE) {
        if (!$isEcho) {

            return $this->comment->getCommentAuthorEmail();
        }

        echo $this->comment->getCommentAuthorEmail();
    }

    public function getCommentDate($isEcho = \TRUE) {
        if (!$isEcho) {

            return $this->comment->getCommentDate();
        }

        echo $this->comment->getCommentDate();
    }

    public function getCommentContents($isEcho = \TRUE) {
        if (!$isEcho) {

            return $this->comment->getCommentContents();
        }

        echo $this->comment->getCommentContents();
    }

}
