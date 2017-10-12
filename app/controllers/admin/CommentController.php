<?php
/**
 * CommentController.php
 */

namespace SoftnCMS\controllers\admin;

use SoftnCMS\classes\constants\Constants;
use SoftnCMS\controllers\CUDControllerAbstract;
use SoftnCMS\controllers\ViewController;
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
        if (Form::submit(Constants::FORM_CREATE)) {
            $form = $this->form();
            
            if (!empty($form)) {
                $commentsManager = new CommentsManager();
                $comment         = Arrays::get($form, 'comment');
                
                if ($commentsManager->create($comment)) {
                    Messages::addSuccess(__('Comentario publicado correctamente.'), TRUE);
                    $optionsManager = new OptionsManager();
                    Util::redirect($optionsManager->getSiteUrl() . 'admin/comment');
                }
            }
            
            Messages::addDanger(__('Error al publicar el comentario.'));
        }
        
        $comment = new Comment();
        $comment->setCommentUserId(LoginManager::getSession());
        ViewController::sendViewData('isUpdate', FALSE);
        ViewController::sendViewData('comment', $comment);
        ViewController::sendViewData('title', __('Publicar nuevo comentario'));
        ViewController::view('form');
    }
    
    protected function form() {
        $inputs = $this->filterInputs();
        
        if (empty($inputs)) {
            return FALSE;
        }
        
        $userId  = Arrays::get($inputs, CommentsManager::COMMENT_USER_ID);
        $comment = new Comment();
        $comment->setId(Arrays::get($inputs, CommentsManager::COLUMN_ID));
        $comment->setCommentStatus(Arrays::get($inputs, CommentsManager::COMMENT_STATUS));
        $comment->setCommentContents(Arrays::get($inputs, CommentsManager::COMMENT_CONTENTS));
        $comment->setPostId(NULL);
        $comment->setCommentAuthor(NULL);
        $comment->setCommentAuthorEmail(NULL);
        $comment->setCommentDate(NULL);
        $comment->setCommentUserId(NULL);
        
        if (empty($userId)) {
            $comment->setCommentAuthor(Arrays::get($inputs, CommentsManager::COMMENT_AUTHOR));
            $comment->setCommentAuthorEmail(Arrays::get($inputs, CommentsManager::COMMENT_AUTHOR_EMAIL));
        }
        
        if (Form::submit(Constants::FORM_CREATE)) {
            $usersManager = new UsersManager();
            $user         = $usersManager->searchById(LoginManager::getSession());
            $comment->setCommentAuthor($user->getUserName());
            $comment->setCommentAuthorEmail($user->getUserEmail());
            $comment->setCommentUserId($user->getId());
            $comment->setCommentDate(Util::dateNow());
            $comment->setPostId(Arrays::get($inputs, CommentsManager::POST_ID));
        }
        
        return ['comment' => $comment];
    }
    
    protected function filterInputs() {
        Form::setInput([
            InputAlphanumericBuilder::init(CommentsManager::COMMENT_AUTHOR)
                                    ->setRequire(FALSE)
                                    ->build(),
            InputEmailBuilder::init(CommentsManager::COMMENT_AUTHOR_EMAIL)
                             ->setRequire(FALSE)
                             ->build(),
            InputIntegerBuilder::init(CommentsManager::COMMENT_USER_ID)
                               ->build(),
            InputIntegerBuilder::init(CommentsManager::COLUMN_ID)
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
        $commentsManager = new CommentsManager();
        $comment         = $commentsManager->searchById($id);
        
        if (empty($comment)) {
            $optionsManager = new OptionsManager();
            Messages::addDanger(__('El comentario no existe.'), TRUE);
            Util::redirect($optionsManager->getSiteUrl() . 'admin/comment');
        } else {
            if (Form::submit(Constants::FORM_UPDATE)) {
                $form = $this->form();
                
                if (empty($form)) {
                    Messages::addDanger(__('Error en los campos del comentario.'));
                } else {
                    $comment = Arrays::get($form, 'comment');
                    
                    if ($commentsManager->updateByColumnId($comment)) {
                        Messages::addSuccess(__('Comentario actualizado correctamente.'));
                        $comment = $commentsManager->searchById($comment->getId());
                    } else {
                        Messages::addDanger(__('Error al actualizar el comentario.'));
                    }
                }
            }
            
            ViewController::sendViewData('isUpdate', TRUE);
            ViewController::sendViewData('comment', $comment);
            ViewController::sendViewData('title', __('Actualizar comentario'));
            ViewController::view('form');
        }
    }
    
    public function delete($id) {
        $commentsManager = new CommentsManager();
        
        if (empty($commentsManager->deleteById($id))) {
            Messages::addDanger(__('Error al borrar el comentario.'));
        } else {
            Messages::addSuccess(__('Comentario borrado correctamente.'));
        }
        
        parent::delete($id);
    }
    
    protected function read() {
        $commentsManager = new CommentsManager();
        $count           = $commentsManager->count();
        $limit           = parent::pagination($count);
        
        ViewController::sendViewData('comments', $commentsManager->searchAll($limit));
    }
    
}
