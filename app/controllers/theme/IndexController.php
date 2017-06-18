<?php
/**
 * IndexController.php
 */

namespace SoftnCMS\controllers\theme;

use SoftnCMS\controllers\ControllerAbstract;
use SoftnCMS\controllers\template\PostTemplate;
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\managers\PostsManager;
use SoftnCMS\models\tables\Post;
use SoftnCMS\util\Escape;

/**
 * Class IndexController
 * @author Nicolás Marulanda P.
 */
class IndexController extends ControllerAbstract {
    
    public function index() {
        $this->read();
        ViewController::view('index');
    }
    
    protected function read() {
        $postsManager = new PostsManager();
        $count        = $postsManager->count();
        $pagination   = parent::pagination($count);
        $filters      = [];
        
        if ($pagination !== FALSE) {
            $filters['limit'] = $pagination;
        }
        
        $posts = $postsManager->read($filters);
        array_walk($posts, function(Post $post) {
            $post->setPostContents(Escape::htmlDecode($post->getPostContents()));
        });
        
        $postsTemplate = array_map(function(Post $post) {
            return new PostTemplate($post, TRUE);
        }, $posts);
        
        ViewController::sendViewData('posts', $postsTemplate);
    }
    
}
