<?php
/**
 * PostController.phproller.php
 */

namespace SoftnCMS\controllers\admin;

use SoftnCMS\controllers\CUDControllerAbstract;
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\ManagerAbstract;
use SoftnCMS\models\managers\PostsManager;
use SoftnCMS\models\tables\Post;
use SoftnCMS\util\Arrays;
use SoftnCMS\util\Messages;
use SoftnCMS\util\Util;

/**
 * Class PostController
 * @author Nicolás Marulanda P.
 */
class PostController extends CUDControllerAbstract {
    
    public function create() {
        $result = FALSE;
        
        if (Arrays::get($_POST, PostsManager::FORM_CREATE)) {
            $postsManager = new PostsManager();
            $messages     = 'Error al publicar la entrada.';
            $typeMessage  = Messages::TYPE_DANGER;
            $post         = $this->form();
            $result       = $postsManager->create($post);
            
            if ($result) {
                $messages    = 'Entrada publicada correctamente.';
                $typeMessage = Messages::TYPE_SUCCESS;
                Messages::sendMessagesView($messages, $typeMessage);
                //TODO: temporalmente, hasta crear una redirección a la pagina "update".
                $this->index();
            }
            
            Messages::sendMessagesView($messages, $typeMessage);
        }
        
        if (!$result) {
            ViewController::sendViewData('post', new Post());
            ViewController::sendViewData('title', 'Publicar nueva entrada');
            ViewController::view('form');
        }
    }
    
    protected function form() {
        $post     = new Post();
        $date     = Util::dateNow();
        $isCreate = Arrays::get($_POST, PostsManager::FORM_CREATE);
        
        $post->setId(Arrays::get($_POST, PostsManager::ID));
        $post->setCommentCount(NULL);
        $post->setPostUpdate($date);
        $post->setPostTitle(Arrays::get($_POST, PostsManager::POST_TITLE));
        $post->setPostStatus(Arrays::get($_POST, PostsManager::POST_STATUS));
        $post->setCommentStatus(Arrays::get($_POST, PostsManager::COMMENT_STATUS));
        $post->setPostContents(Arrays::get($_POST, PostsManager::POST_CONTENTS));
        //TODO: temporalmente establecido al id 1.
        $post->setUserID(1);
        
        if ($isCreate) {
            $post->setCommentCount(0);
            $post->setPostDate($date);
        }
        
        return $post;
    }
    
    public function index() {
        $this->read();
        ViewController::view('index');
    }
    
    protected function read() {
        $postsManager = new PostsManager();
        ViewController::sendViewData('posts', $postsManager->read());
    }
    
    public function update($id) {
        $postsManager = new PostsManager();
        
        if (Arrays::get($_POST, PostsManager::FORM_UPDATE)) {
            $typeMessage = Messages::TYPE_DANGER;
            $messages    = 'Error al actualizar la entrada.';
            $post        = $this->form();
            
            if ($postsManager->update($post)) {
                $messages    = 'Entrada actualizada correctamente.';
                $typeMessage = Messages::TYPE_SUCCESS;
            }
            
            Messages::sendMessagesView($messages, $typeMessage);
        }
        
        $post = $postsManager->searchById($id);
        
        ViewController::sendViewData('post', $post);
        ViewController::sendViewData('title', 'Actualizar entrada');
        ViewController::view('form');
    }
    
    public function delete($id) {
        $messages     = 'Error al borrar la entrada.';
        $typeMessage  = Messages::TYPE_DANGER;
        $postsManager = new PostsManager();
        
        if ($postsManager->delete($id)) {
            $messages    = 'Entrada borrada correctamente.';
            $typeMessage = Messages::TYPE_SUCCESS;
        }
        
        Messages::sendMessagesView($messages, $typeMessage);
        $this->index();
    }
    
}
