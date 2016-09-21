<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SoftnCMS\controllers\themes;

use SoftnCMS\controllers\Controller;
use SoftnCMS\controllers\Messages;
use SoftnCMS\controllers\Pagination;
use SoftnCMS\models\admin\Post;
use SoftnCMS\models\admin\CommentInsert;
use SoftnCMS\models\theme\PostCommentsTemplate;
use SoftnCMS\models\theme\PostsTemplate;
use SoftnCMS\models\theme\PostTemplate;
use SoftnCMS\models\theme\Template;

/**
 * Description of PostContoller
 *
 * @author Nicolás Marulanda P.
 */
class PostController extends Controller {
    
    /**
     * Metodo llamado por la función INDEX.
     *
     * @param array $data Lista de argumentos.
     *
     * @return array
     */
    protected function dataIndex($data) {
        global $urlSite;

        //Se comprueba si se ha enviado un comentario.
        $this->postComment($data['id']);
        $post = Post::selectByID($data['id']);

        //En caso de que el post no exista.
        if ($post === \FALSE) {
            \header("Location: $urlSite");
            exit();
        }

        $template = new Template();
        $template->concatTitle($post->getPostTitle());
        
        $countData = PostCommentsTemplate::count($post->getID());
        $pagination = new Pagination($data['paged'], $countData);
        $commentLimit = $pagination->getBeginRow() . ',' . $pagination->getRowCount();
        $postTemplate = new PostTemplate($post, $commentLimit);

        return [
            //Retorno un array para mantener la misma sintaxis.
            'posts' => [$postTemplate],
            'template' => $template,
            'pagination' => $pagination
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
