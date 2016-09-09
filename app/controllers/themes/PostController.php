<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SoftnCMS\controllers\themes;

use SoftnCMS\controllers\themes\Template;
use SoftnCMS\controllers\Messages;
use SoftnCMS\models\admin\Post;
use SoftnCMS\models\admin\Comments;
use SoftnCMS\models\admin\CommentInsert;
use SoftnCMS\models\admin\PostsCategories;
use SoftnCMS\models\admin\PostsTerms;

/**
 * Description of PostContoller
 *
 * @author Nicolás Marulanda P.
 */
class PostController {

    public function index($id) {
        return ['data' => $this->dataIndex($id)];
    }

    protected function dataIndex($id) {
        global $urlSite;

        //Se comprueba si se ha enviado un comentario.
        $this->postComment($id);
        $post = Post::selectByID($id);
        
        //En caso de que el post no exista.
        if($post === \FALSE){
            \header("Location: $urlSite");
            exit();
        }
        
        $template = new Template($post->getPostTitle());
        $categories = PostsCategories::selectByPostID($id);
        $terms = PostsTerms::selectByPostID($id);
        $comments = Comments::selectByPostID($id);

        if ($comments !== \FALSE) {
            $comments = $comments->getAll();
        }

        return [
            'post' => $post,
            'categories' => $categories,
            'terms' => $terms,
            'comments' => $comments,
            'template' => $template
        ];
    }

    private function postComment($id) {
        if (\filter_input(\INPUT_POST, 'publish')) {
            $dataInput = $this->getDataInput();
            $commentUserID = empty($dataInput['commentUserID']) ? 0 : $dataInput['commentUserID'];
            $commentStatus = 0;

            $insert = new CommentInsert($dataInput['commentAutor'], $dataInput['commentAuthorEmail'], $commentStatus, $dataInput['commentContents'], $id, $commentUserID);

            if (!$insert->insert()) {
                Messages::addError('Error al publicar el comentario');
            }
            Messages::addSuccess('Comentario publicado correctamente.');
        }
    }

    private function getDataInput() {
        return [
            'commentAutor' => \filter_input(\INPUT_POST, 'commentAutor'),
            //commentUserID Corresponde al ID del usuario de la sesión.
            'commentUserID' => \filter_input(\INPUT_POST, 'commentUserID'),
            'commentAuthorEmail' => \filter_input(\INPUT_POST, 'commentAuthorEmail'),
            'commentContents' => \filter_input(\INPUT_POST, 'commentContents'),
        ];
    }

}
