<?php
/**
 * CommentController.php
 */

namespace SoftnCMS\controllers\admin;

use SoftnCMS\controllers\CUDControllerAbstract;
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\CRUDManagerAbstract;
use SoftnCMS\models\managers\CommentsManager;
use SoftnCMS\models\managers\LoginManager;
use SoftnCMS\models\managers\OptionsManager;
use SoftnCMS\models\managers\UsersManager;
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
            $messages    = 'Error al publicar el comentario.';
            $typeMessage = Messages::TYPE_DANGER;
            
            if (!empty($form)) {
                $commentsManager = new CommentsManager();
                $comment         = Arrays::get($form, 'comment');
                
                if ($commentsManager->create($comment)) {
                    $showForm    = FALSE;
                    $messages    = 'Comentario publicado correctamente.';
                    $typeMessage = Messages::TYPE_SUCCESS;
                    Messages::addSessionMessage($messages, $typeMessage);
                    $optionsManager = new OptionsManager();
                    Util::redirect($optionsManager->getSiteUrl() . 'admin/comment');
                }
            }
            
            Messages::addMessage($messages, $typeMessage);
        }
        
        if ($showForm) {
            $comment = new Comment();
            $comment->setCommentUserID(LoginManager::getSession());
            ViewController::sendViewData('comment', $comment);
            ViewController::sendViewData('title', 'Publicar nuevo comentario');
            ViewController::view('form');
        }
    }
    
    protected function form() {
        $inputs = $this->filterInputs();
        
        if (empty($inputs)) {
            return FALSE;
        }
        
        $userId  = Arrays::get($inputs, CommentsManager::COMMENT_USER_ID);
        $comment = new Comment();
        $comment->setId(Arrays::get($inputs, CommentsManager::ID));
        $comment->setCommentStatus(Arrays::get($inputs, CommentsManager::COMMENT_STATUS));
        $comment->setCommentContents(Arrays::get($inputs, CommentsManager::COMMENT_CONTENTS));
        $comment->setPostID(NULL);
        $comment->setCommentAuthor(NULL);
        $comment->setCommentAuthorEmail(NULL);
        $comment->setCommentDate(NULL);
        $comment->setCommentUserID(NULL);
        
        if (empty($userId)) {
            $comment->setCommentAuthor(Arrays::get($inputs, CommentsManager::COMMENT_AUTHOR));
            $comment->setCommentAuthorEmail(Arrays::get($inputs, CommentsManager::COMMENT_AUTHOR_EMAIL));
        }
        
        if (Form::submit(CRUDManagerAbstract::FORM_CREATE)) {
            $usersManager = new UsersManager();
            $user         = $usersManager->searchById(LoginManager::getSession());
            $comment->setCommentAuthor($user->getUserName());
            $comment->setCommentAuthorEmail($user->getUserEmail());
            $comment->setCommentUserID($user->getId());
            $comment->setCommentDate(Util::dateNow());
            $comment->setPostID(Arrays::get($inputs, CommentsManager::POST_ID));
        }
        
        return ['comment' => $comment];
    }
    
    protected function filterInputs() {
        Form::setINPUT([
            InputAlphanumericBuilder::init(CommentsManager::COMMENT_AUTHOR)
                                    ->setRequire(FALSE)
                                    ->build(),
            InputEmailBuilder::init(CommentsManager::COMMENT_AUTHOR_EMAIL)
                             ->setRequire(FALSE)
                             ->build(),
            InputIntegerBuilder::init(CommentsManager::COMMENT_USER_ID)
                               ->build(),
            InputIntegerBuilder::init(CommentsManager::ID)
                               ->build(),
            InputIntegerBuilder::init(CommentsManager::POST_ID)
                               ->build(),
            InputBooleanBuilder::init(CommentsManager::COMMENT_STATUS)
                               ->build(),
            InputHtmlBuilder::init(CommentsManager::COMMENT_CONTENTS)
                            ->build(),
        ]);
        
        return Form::inputFilter();
    }
    
    public function update($id) {
        $typeMessage     = Messages::TYPE_DANGER;
        $messages        = 'El comentario no existe.';
        $commentsManager = new CommentsManager();
        $comment         = $commentsManager->searchById($id);
        
        if (empty($comment)) {
            $optionsManager = new OptionsManager();
            Messages::addSessionMessage($messages, $typeMessage);
            Util::redirect($optionsManager->getSiteUrl() . 'admin/comment');
        } else {
            if (Form::submit(CRUDManagerAbstract::FORM_UPDATE)) {
                $messages = 'Error al actualizar el comentario.';
                $form     = $this->form();
                
                if (!empty($form)) {
                    $comment = Arrays::get($form, 'comment');
                    
                    if ($commentsManager->update($comment)) {
                        $messages    = 'Comentario actualizado correctamente.';
                        $typeMessage = Messages::TYPE_SUCCESS;
                        $comment     = $commentsManager->searchById($comment->getId());
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
    
}
