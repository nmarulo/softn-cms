<?php
/**
 * CommentController.php
 */

namespace SoftnCMS\controllers\admin;

use SoftnCMS\classes\constants\Constants;
use SoftnCMS\models\managers\CommentsManager;
use SoftnCMS\models\managers\LoginManager;
use SoftnCMS\models\managers\UsersManager;
use SoftnCMS\models\tables\Comment;
use SoftnCMS\util\controller\ControllerAbstract;
use SoftnCMS\util\form\builders\InputAlphanumericBuilder;
use SoftnCMS\util\form\builders\InputBooleanBuilder;
use SoftnCMS\util\form\builders\InputEmailBuilder;
use SoftnCMS\util\form\builders\InputHtmlBuilder;
use SoftnCMS\util\form\builders\InputIntegerBuilder;
use SoftnCMS\util\Messages;
use SoftnCMS\util\Token;
use SoftnCMS\util\Util;

/**
 * Class CommentController
 * @author Nicolás Marulanda P.
 */
class CommentController extends ControllerAbstract {
    
    public function create() {
        if ($this->checkSubmit(Constants::FORM_CREATE)) {
            if ($this->isValidForm()) {
                $commentsManager = new CommentsManager();
                $comment         = $this->getForm('comment');
                
                if ($commentsManager->create($comment)) {
                    Messages::addSuccess(__('Comentario publicado correctamente.'), TRUE);
                    $this->redirectToAction('index');
                }
            }
            
            Messages::addDanger(__('Error al publicar el comentario.'));
        }
        
        $comment = new Comment();
        $comment->setCommentUserId(LoginManager::getSession());
        $this->sendDataView([
            'isUpdate' => FALSE,
            'comment'  => $comment,
            'title'    => __('Publicar nuevo comentario'),
        ]);
        $this->view('form');
    }
    
    public function update($id) {
        $commentsManager = new CommentsManager();
        $comment         = $commentsManager->searchById($id);
        
        if (empty($comment)) {
            Messages::addDanger(__('El comentario no existe.'), TRUE);
            $this->redirectToAction('index');
        } elseif ($this->checkSubmit(Constants::FORM_UPDATE)) {
            if ($this->isValidForm()) {
                $comment = $this->getForm('comment');
                
                if ($commentsManager->updateByColumnId($comment)) {
                    Messages::addSuccess(__('Comentario actualizado correctamente.'));
                    $comment = $commentsManager->searchById($comment->getId());
                } else {
                    Messages::addDanger(__('Error al actualizar el comentario.'));
                }
            } else {
                Messages::addDanger(__('Error en los campos del comentario.'));
            }
        }
        
        $this->sendDataView([
            'isUpdate' => TRUE,
            'comment'  => $comment,
            'title'    => __('Actualizar comentario'),
        ]);
        $this->view('form');
    }
    
    public function delete($id) {
        if (Token::check()) {
            $commentsManager = new CommentsManager();
            $result          = $commentsManager->deleteById($id);
            $rowCount        = $commentsManager->getRowCount();
            
            if ($rowCount === 0) {
                Messages::addWarning(__("No existe ningún comentario."), TRUE);
            } elseif ($result) {
                Messages::addSuccess(__('Comentario borrado correctamente.'), TRUE);
            } else {
                Messages::addDanger(__('Error al borrar el comentario.'), TRUE);
            }
        }
        
        $this->redirectToAction('index');
    }
    
    public function index() {
        $commentsManager = new CommentsManager();
        $count           = $commentsManager->count();
        
        $this->sendDataView([
            'comments' => $commentsManager->searchAll($this->rowsPages($count)),
        ]);
        $this->view();
    }
    
    protected function formToObject() {
        $userId  = $this->getInput(CommentsManager::COMMENT_USER_ID);
        $comment = new Comment();
        $comment->setId($this->getInput(CommentsManager::COLUMN_ID));
        $comment->setCommentStatus($this->getInput(CommentsManager::COMMENT_STATUS));
        $comment->setCommentContents($this->getInput(CommentsManager::COMMENT_CONTENTS));
        $comment->setPostId(NULL);
        $comment->setCommentAuthor(NULL);
        $comment->setCommentAuthorEmail(NULL);
        $comment->setCommentDate(NULL);
        $comment->setCommentUserId(NULL);
        
        if (empty($userId)) {
            $comment->setCommentAuthor($this->getInput(CommentsManager::COMMENT_AUTHOR));
            $comment->setCommentAuthorEmail($this->getInput(CommentsManager::COMMENT_AUTHOR_EMAIL));
        }
        
        if ($this->checkSubmit(Constants::FORM_CREATE)) {
            $usersManager = new UsersManager();
            $user         = $usersManager->searchById(LoginManager::getSession());
            $comment->setCommentAuthor($user->getUserName());
            $comment->setCommentAuthorEmail($user->getUserEmail());
            $comment->setCommentUserId($user->getId());
            $comment->setCommentDate(Util::dateNow());
            $comment->setPostId($this->getInput(CommentsManager::POST_ID));
        }
        
        return ['comment' => $comment];
    }
    
    protected function formInputsBuilders() {
        return [
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
        ];
    }
    
}
