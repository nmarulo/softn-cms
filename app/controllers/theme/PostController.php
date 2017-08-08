<?php
/**
 * PostController.php
 */

namespace SoftnCMS\controllers\theme;

use SoftnCMS\controllers\template\PostTemplate;
use SoftnCMS\controllers\ThemeControllerAbstract;
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\CRUDManagerAbstract;
use SoftnCMS\models\managers\CommentsManager;
use SoftnCMS\models\managers\LoginManager;
use SoftnCMS\models\managers\OptionsManager;
use SoftnCMS\models\managers\PostsManager;
use SoftnCMS\models\managers\UsersManager;
use SoftnCMS\models\tables\Comment;
use SoftnCMS\util\Arrays;
use SoftnCMS\util\Escape;
use SoftnCMS\util\form\builders\InputAlphanumericBuilder;
use SoftnCMS\util\form\builders\InputEmailBuilder;
use SoftnCMS\util\form\builders\InputIntegerBuilder;
use SoftnCMS\util\form\Form;
use SoftnCMS\util\Util;

/**
 * Class PostController
 * @author Nicolás Marulanda P.
 */
class PostController extends ThemeControllerAbstract {
    
    public function index($id) {
        $this->comment();
        parent::index($id);
    }
    
    private function comment() {
        if (Form::submit(CRUDManagerAbstract::FORM_SUBMIT)) {
            $form = $this->form();
            
            if (!empty($form)) {
                $commentsManager = new CommentsManager();
                $comment         = Arrays::get($form, 'comment');
                
                if ($commentsManager->create($comment)) {
                    //TODO: mensaje
                }
            }
        }
    }
    
    private function form() {
        $inputs = $this->filterInputs();
        
        if (empty($inputs)) {
            return FALSE;
        }
        
        $comment = new Comment();
        $comment->setCommentContents(Arrays::get($inputs, CommentsManager::COMMENT_CONTENTS));
        $comment->setCommentAuthorEmail(Arrays::get($inputs, CommentsManager::COMMENT_AUTHOR_EMAIL));
        $comment->setCommentAuthor(Arrays::get($inputs, CommentsManager::COMMENT_AUTHOR));
        $comment->setCommentUserID(Arrays::get($inputs, CommentsManager::COMMENT_USER_ID));
        $comment->setCommentStatus(0);
        $comment->setCommentDate(Util::dateNow());
        $comment->setPostID(Arrays::get($inputs, CommentsManager::POST_ID));
        
        if (LoginManager::isLogin()) {
            $usersManager = new UsersManager();
            $user         = $usersManager->searchById(LoginManager::getSession());
            $comment->setCommentAuthorEmail($user->getUserEmail());
            $comment->setCommentAuthor($user->getUserName());
        }
        
        return ['comment' => $comment];
    }
    
    private function filterInputs() {
        $isRequire = !LoginManager::isLogin();
        
        Form::setInput([
            InputAlphanumericBuilder::init(CommentsManager::COMMENT_AUTHOR)
                                    ->setRequire($isRequire)
                                    ->build(),
            //commentUserID Corresponde al ID del usuario de la sesión.
            InputIntegerBuilder::init(CommentsManager::COMMENT_USER_ID)
                               ->setRequire(!$isRequire)
                               ->build(),
            InputEmailBuilder::init(CommentsManager::COMMENT_AUTHOR_EMAIL)
                             ->setRequire($isRequire)
                             ->build(),
            InputAlphanumericBuilder::init(CommentsManager::COMMENT_CONTENTS)
                                    ->build(),
            InputIntegerBuilder::init(CommentsManager::POST_ID)
                               ->build(),
        ]);
        
        return Form::inputFilter();
    }
    
    protected function read($id) {
        $postsManager = new PostsManager();
        $post         = $postsManager->searchById($id);
        
        if (empty($post)) {
            $optionsManager = new OptionsManager();
            Util::redirect($optionsManager->getSiteUrl());
        }
        
        $post->setPostContents(Escape::htmlDecode($post->getPostContents()));
        ViewController::sendViewData('post', new PostTemplate($post, TRUE));
    }
    
}
