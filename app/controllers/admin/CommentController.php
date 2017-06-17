<?php
/**
 * CommentController.php
 */

namespace SoftnCMS\controllers\admin;

use SoftnCMS\controllers\CUDControllerAbstract;
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\CRUDManagerAbstract;
use SoftnCMS\models\managers\CommentsManager;
use SoftnCMS\models\tables\Comment;
use SoftnCMS\util\Arrays;
use SoftnCMS\util\form\builders\InputAlphanumericBuilder;
use SoftnCMS\util\form\builders\InputBooleanBuilder;
use SoftnCMS\util\form\builders\InputEmailBuilder;
use SoftnCMS\util\form\builders\InputHtmlBuilder;
use SoftnCMS\util\form\builders\InputIntegerBuilder;
use SoftnCMS\util\form\Form;
use SoftnCMS\util\Messages;
use SoftnCMS\util\Util;

/**
 * Class CommentController
 * @author Nicolás Marulanda P.
 */
class CommentController extends CUDControllerAbstract {
    
    public function create() {
        $showForm = TRUE;
        
        if (Form::submit(CRUDManagerAbstract::FORM_CREATE)) {
            $form        = $this->form();
            $messages    = 'Error al publicar la .';
            $typeMessage = Messages::TYPE_DANGER;
            
            if (!empty($form)) {
                $commentsManager = new CommentsManager();
                $comment         = Arrays::get($form, 'comment');
                
                if ($commentsManager->create($comment)) {
                    $showForm    = FALSE;
                    $messages    = 'Comentario publicado correctamente.';
                    $typeMessage = Messages::TYPE_SUCCESS;
                    Messages::addMessage($messages, $typeMessage);
                    $this->index();
                }
            }
            
            Messages::addMessage($messages, $typeMessage);
        }
        
        if ($showForm) {
            ViewController::sendViewData('comment', new Comment());
            ViewController::sendViewData('title', 'Publicar nuevo comentario');
            ViewController::view('form');
        }
    }
    
    protected function form() {
        $inputs = $this->filterInputs();
        
        if (empty($inputs)) {
            return FALSE;
        }
        
        $comment  = new Comment();
        $comment->setId(Arrays::get($inputs, CommentsManager::ID));
        $comment->setPostID(Arrays::get($inputs, CommentsManager::POST_ID));
        $comment->setCommentAuthor(Arrays::get($inputs, CommentsManager::COMMENT_AUTHOR));
        $comment->setCommentStatus(Arrays::get($inputs, CommentsManager::COMMENT_STATUS));
        $comment->setCommentAuthorEmail(Arrays::get($inputs, CommentsManager::COMMENT_AUTHOR_EMAIL));
        $comment->setCommentContents(Arrays::get($inputs, CommentsManager::COMMENT_CONTENTS));
        $comment->setCommentDate(NULL);
        $comment->setCommentUserID(Arrays::get($inputs, CommentsManager::COMMENT_USER_ID));
        
        if (Form::submit(CRUDManagerAbstract::FORM_CREATE)) {
            $comment->setCommentDate(Util::dateNow());
        }
        
        return ['comment' => $comment];
    }
    
    protected function filterInputs() {
        Form::setINPUT([
            InputIntegerBuilder::init(CommentsManager::ID)
                               ->build(),
            InputIntegerBuilder::init(CommentsManager::POST_ID)
                               ->build(),
            InputAlphanumericBuilder::init(CommentsManager::COMMENT_AUTHOR)
                                    ->build(),
            InputBooleanBuilder::init(CommentsManager::COMMENT_STATUS)
                               ->build(),
            InputEmailBuilder::init(CommentsManager::COMMENT_AUTHOR_EMAIL)
                             ->setRequire(FALSE)
                             ->build(),
            InputHtmlBuilder::init(CommentsManager::COMMENT_CONTENTS)
                            ->build(),
            InputIntegerBuilder::init(CommentsManager::COMMENT_USER_ID)
                               ->build(),
        ]);
        
        return Form::inputFilter();
    }
    
    public function index() {
        $this->read();
        ViewController::view('index');
    }
    
    protected function read() {
        $filters         = [];
        $commentsManager = new CommentsManager();
        $count           = $commentsManager->count();
        $pagination      = parent::pagination($count);
        
        if ($pagination !== FALSE) {
            $filters['limit'] = $pagination;
        }
        
        ViewController::sendViewData('comments', $commentsManager->read($filters));
    }
    
    public function update($id) {
        $typeMessage     = Messages::TYPE_DANGER;
        $messages        = 'El comentario no existe.';
        $commentsManager = new CommentsManager();
        $comment         = $commentsManager->searchById($id);
        
        if (empty($comment)) {
            Messages::addMessage($messages, $typeMessage);
            $this->index();
        } else {
            if (Form::submit(CRUDManagerAbstract::FORM_UPDATE)) {
                $messages = 'Error al actualizar el comentario.';
                $form     = $this->form();
                
                if (!empty($form)) {
                    $comment = Arrays::get($form, 'comment');
                    
                    if ($commentsManager->update($comment)) {
                        $messages    = ' actualizada correctamente.';
                        $typeMessage = Messages::TYPE_SUCCESS;
                    }
                }
                
                Messages::addMessage($messages, $typeMessage);
            }
            
            ViewController::sendViewData('comment', $comment);
            ViewController::sendViewData('title', 'Actualizar comentario');
            ViewController::view('form');
        }
    }
    
    public function delete($id) {
        $messages        = 'Error al borrar el comentario.';
        $typeMessage     = Messages::TYPE_DANGER;
        $commentsManager = new CommentsManager();
        
        if (!empty($commentsManager->delete($id))) {
            $messages    = 'Comentario borrado correctamente.';
            $typeMessage = Messages::TYPE_SUCCESS;
        }
        
        Messages::addMessage($messages, $typeMessage);
        parent::delete($id);
    }
    
}
