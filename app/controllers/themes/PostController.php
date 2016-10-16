<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SoftnCMS\controllers\themes;

use SoftnCMS\controllers\Controller;
use SoftnCMS\controllers\Form;
use SoftnCMS\controllers\Messages;
use SoftnCMS\controllers\Pagination;
use SoftnCMS\controllers\Token;
use SoftnCMS\Helpers\ArrayHelp;
use SoftnCMS\Helpers\Helps;
use SoftnCMS\models\admin\Post;
use SoftnCMS\models\admin\CommentInsert;
use SoftnCMS\models\Login;
use SoftnCMS\models\theme\PostCommentsTemplate;
use SoftnCMS\models\theme\PostTemplate;
use SoftnCMS\models\theme\Template;

/**
 * Description of PostContoller
 * @author Nicolás Marulanda P.
 */
class PostController extends Controller {
    
    /**
     * Método llamado por la función INDEX.
     *
     * @param array $data Lista de argumentos.
     *
     * @return array
     */
    protected function dataIndex($data) {
        $id   = ArrayHelp::get($data, 'id');
        $post = Post::selectByID($id);
        
        //En caso de que el post no exista.
        if ($post === FALSE) {
            Helps::redirect();
        }
        
        //Se comprueba si se ha enviado un comentario.
        $this->postComment($id);
        
        Template::setTitle(' | ' . $post->getPostTitle());
        
        //        $countData    = PostCommentsTemplate::count($post->getID());
        //        $pagination   = new Pagination(ArrayHelp::get($data, 'paged'), $countData);
        //        $commentLimit = $pagination->getBeginRow() . ',' . $pagination->getRowCount();
        $postTemplate = new PostTemplate($id);
        
        return [
            //Retorno un array para mantener la misma sintaxis.
            'posts' => [$postTemplate],
        ];
    }
    
    /**
     * @param int $id Identificador del Post.
     */
    private function postComment($id) {
        if (Form::submit('publish')) {
            $dataInput = $this->getDataInput();
            
            if ($dataInput === FALSE) {
                Messages::addError('Error al publicar el comentario');
            } else {
                $commentUserID = $dataInput['commentUserID'];
                //Si es un usuario registrado el comentario es aprobado.
                $commentStatus = empty($commentUserID) ? 0 : 1;
                
                $insert = new CommentInsert($dataInput['commentAuthor'], $dataInput['commentAuthorEmail'], $commentStatus, $dataInput['commentContents'], $id, $commentUserID);
                
                if ($insert->insert()) {
                    Messages::addSuccess('Comentario publicado correctamente.');
                } else {
                    Messages::addError('Error al publicar el comentario');
                }
            }
        }
    }
    
    private function getDataInput() {
        if (Token::check()) {
            /*
             * Si hay una sesión activa,
             * no sera obligatorio los campos "commentAuthor"
             * y "commentAuthorEmail".
             */
            $login = !Login::isLogin();
            
            Form::addInputAlphanumeric('commentAuthor', $login);
            //commentUserID Corresponde al ID del usuario de la sesión.
            Form::addInputInteger('commentUserID');
            Form::addInputEmail('commentAuthorEmail', $login);
            Form::addInputAlphanumeric('commentContents', TRUE);
            
            return Form::postInput();
        }
        
        return FALSE;
    }
    
}
