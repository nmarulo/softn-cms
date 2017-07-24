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
        if (Form::submit(CRUDManagerAbstract::FORM_CREATE)) {
            $form = $this->form();
            
            if (!empty($form)) {
                $commentsManager = new CommentsManager();
                $comment         = Arrays::get($form, 'comment');
                
                if ($commentsManager->create($comment)) {
                    Messages::addSuccess('Comentario publicado correctamente.', TRUE);
                    $optionsManager = new OptionsManager();
                    Util::redirect($optionsManager->getSiteUrl() . 'admin/comment');
                }
            }
            
            Messages::addDanger('Error al publicar el comentario.');
        }
        
        $comment = new Comment();
        $comment->setCommentUserID(LoginManager::getSession());
        ViewController::sendViewData('comment', $comment);
        ViewController::sendViewData('title', 'Publicar nuevo comentario');
        ViewController::view('form');
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
        $commentsManager = new CommentsManager();
        $comment         = $commentsManager->searchById($id);
        
        if (empty($comment)) {
            $optionsManager = new OptionsManager();
            Messages::addDanger('El comentario no existe.', TRUE);
            Util::redirect($optionsManager->getSiteUrl() . 'admin/comment');
        } else {
            if (Form::submit(CRUDManagerAbstract::FORM_UPDATE)) {
                $form = $this->form();
                
                if (empty($form)) {
                    Messages::addDanger('Error en los campos del comentario.');
                } else {
                    $comment = Arrays::get($form, 'comment');
                    
                    if ($commentsManager->update($comment)) {
                        Messages::addSuccess('Comentario actualizado correctamente.');
                        $comment = $commentsManager->searchById($comment->getId());
                    } else {
                        Messages::addDanger('Error al actualizar el comentario.');
                    }
                }
            }
            
            ViewController::sendViewData('comment', $comment);
            ViewController::sendViewData('title', 'Actualizar comentario');
            ViewController::view('form');
        }
    }
    
    public function delete($id) {
        $commentsManager = new CommentsManager();
        
        if (empty($commentsManager->delete($id))) {
            Messages::addDanger('Error al borrar el comentario.');
        } else {
            Messages::addSuccess('Comentario borrado correctamente.');
        }
        
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
