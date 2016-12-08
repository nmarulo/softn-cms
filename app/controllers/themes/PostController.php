<?php

/**
 * Modulo controlador: Pagina de entrada de la plantilla de la aplicación.
 */

namespace SoftnCMS\controllers\themes;

use SoftnCMS\controllers\Controller;
use SoftnCMS\controllers\Form;
use SoftnCMS\controllers\Messages;
use SoftnCMS\controllers\Token;
use SoftnCMS\helpers\ArrayHelp;
use SoftnCMS\helpers\form\builders\InputAlphanumericBuilder;
use SoftnCMS\helpers\form\builders\InputEmailBuilder;
use SoftnCMS\helpers\form\builders\InputIntegerBuilder;
use SoftnCMS\helpers\Helps;
use SoftnCMS\models\admin\Post;
use SoftnCMS\models\admin\CommentInsert;
use SoftnCMS\models\Login;
use SoftnCMS\models\theme\PostTemplate;
use SoftnCMS\models\theme\Template;

/**
 * Clase PostController de la pagina de entradas de la plantilla de la aplicación.
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
        //Se agrega el nombre al titulo de la pagina
        Template::setTitle(' | ' . $post->getPostTitle());
        
        $postTemplate = new PostTemplate($id);
        
        return [
            //Retorno un array para mantener la misma sintaxis.
            'posts' => [$postTemplate],
        ];
    }
    
    /**
     * Método que comprueba y publica si se ha enviado un comentario.
     *
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
    
    /**
     * Método que obtiene los datos de los campos INPUT del formulario.
     * @return array|bool
     */
    private function getDataInput() {
        if (Token::check()) {
            /*
             * Si hay una sesión activa, no sera obligatorio los campos "commentAuthor"
             * y "commentAuthorEmail".
             */
            $isRequire = !Login::isLogin();
            
            Form::setINPUT([
                InputAlphanumericBuilder::init('commentAuthor')
                                        ->setRequire($isRequire)
                                        ->build(),
                //commentUserID Corresponde al ID del usuario de la sesión.
                InputIntegerBuilder::init('commentUserID')
                                   ->setRequire(!$isRequire)
                                   ->build(),
                InputEmailBuilder::init('commentAuthorEmail')
                                 ->setRequire($isRequire)
                                 ->build(),
                InputAlphanumericBuilder::init('commentContents')
                                        ->build(),
            ]);
            
            return Form::inputFilter();
        }
        
        return FALSE;
    }
    
}
