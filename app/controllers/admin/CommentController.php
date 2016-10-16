<?php

/**
 * Modulo del controlador de la pagina de comentarios.
 */

namespace SoftnCMS\controllers\admin;

use SoftnCMS\controllers\BaseController;
use SoftnCMS\controllers\Form;
use SoftnCMS\controllers\Messages;
use SoftnCMS\controllers\Pagination;
use SoftnCMS\controllers\Sanitize;
use SoftnCMS\controllers\Token;
use SoftnCMS\Helpers\ArrayHelp;
use SoftnCMS\Helpers\Helps;
use SoftnCMS\models\admin\Comments;
use SoftnCMS\models\admin\Comment;
use SoftnCMS\models\admin\CommentUpdate;
use SoftnCMS\models\admin\CommentInsert;
use SoftnCMS\models\admin\CommentDelete;
use SoftnCMS\models\admin\template\Template;
use SoftnCMS\models\Login;

/**
 * Clase del controlador de la pagina de comentarios.
 * @author Nicolás Marulanda P.
 */
class CommentController extends BaseController {
    
    /**
     * Metodo llamado por la función INDEX.
     *
     * @param array $data Lista de argumentos.
     *
     * @return array
     */
    protected function dataIndex($data) {
        $output     = [];
        $countData  = Comments::count();
        $pagination = new Pagination(ArrayHelp::get($data, 'paged'), $countData);
        $limit      = $pagination->getBeginRow() . ',' . $pagination->getRowCount();
        $comments   = Comments::selectByLimit($limit);
        Template::setPagination($pagination);
        
        if ($comments !== \FALSE) {
            $output = $comments->getAll();
        }
        
        foreach ($output as $value) {
            $contents = Sanitize::clearTags($value->getCommentContents());
            
            if (isset($contents{30})) {
                $contents = substr($contents, 0, 30) . ' [...]';
            }
            $value->setCommentContents($contents);
        }
        
        return [
            'comments' => $output,
        ];
    }
    
    /**
     * Metodo llamado por la función INSERT.
     * @return array
     */
    protected function dataInsert() {
        if (Form::submit('publish')) {
            $dataInput = $this->getDataInput();
            
            if ($dataInput !== FALSE) {
                $insert = new CommentInsert($dataInput['commentAuthor'], $dataInput['commentAuthorEmail'], $dataInput['commentStatus'], $dataInput['commentContents'], $dataInput['postID'], Login::getSession());
                
                if ($insert->insert()) {
                    Messages::addSuccess('Comentario publicado correctamente.');
                    //Si es correcto se muestra el comentario en la pagina de edición.
                    Helps::redirectRoute('update/' . $insert->getLastInsertId());
                }
            }
            Messages::addError('Error al publicar el comentario');
        }
        
        return [
            //Datos por defecto a mostrar en el formulario.
            'comment' => Comment::defaultInstance(),
        ];
    }
    
    /**
     * Metodo que obtiene los datos de los campos INPUT del formulario.
     * @return array|bool
     */
    protected function getDataInput() {
        if (Token::check()) {
            Form::addInputAlphanumeric('commentAuthor');
            Form::addInputEmail('commentAuthorEmail');
            Form::addInputInteger('postID', TRUE);
            Form::addInputBoolean('commentStatus', TRUE);
            Form::addInputHtml('commentContents', TRUE);
            
            return Form::postInput();
        }
        
        return FALSE;
    }
    
    /**
     * Metodo llamado por la función UPDATE.
     *
     * @param array $data Lista de argumentos.
     *
     * @return array
     */
    protected function dataUpdate($data) {
        $comment = Comment::selectByID(ArrayHelp::get($data, 'id'));
        
        //En caso de que no exista.
        if (empty($comment)) {
            Messages::addError('Error. El comentario no existe.');
            Helps::redirectRoute();
        }
        
        if (Form::submit('update')) {
            $dataInput = $this->getDataInput();
            
            if ($dataInput === FALSE) {
                Messages::addError('Error al actualizar el comentario.');
            } else {
                $update = new CommentUpdate($comment, $dataInput['commentAuthor'], $dataInput['commentAuthorEmail'], $dataInput['commentStatus'], $dataInput['commentContents']);
                
                //Si ocurre un error la función "$update->update()" retorna FALSE.
                if ($update->update()) {
                    Messages::addSuccess('Comentario actualizado correctamente.');
                    $comment = $update->getDataUpdate();
                } else {
                    Messages::addError('Error al actualizar el comentario.');
                }
            }
        }
        
        return [
            //Instancia Comment
            'comment' => $comment,
        ];
    }
    
    /**
     * Metodo llamado por la función DELETE.
     *
     * @param array $data Lista de argumentos.
     */
    protected function dataDelete($data) {
        /*
         * Ya que este metodo no tiene modulo vista propio
         * se carga el modulo vista INDEX, asi que se retornan los datos
         * para esta vista.
         */
        
        $output = FALSE;
        
        if (Token::check()) {
            $delete = new CommentDelete($data['id']);
            $output = $delete->delete();
        }
        
        if ($output) {
            Messages::addSuccess('Comentario borrado correctamente.');
        } elseif ($output === 0) {
            Messages::addWarning('El comentario no existe.');
        } else {
            Messages::addError('Error al borrar el comentario.');
        }
        
    }
    
}
