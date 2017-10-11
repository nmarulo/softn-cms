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
        $postStatus   = TRUE;
        $postsManager = new PostsManager();
        $count        = $postsManager->countByStatus($postStatus);
        $limit        = parent::pagination($count);
        
        $posts         = $postsManager->searchAllByStatus($postStatus, $limit);
        $postsTemplate = array_map(function(Post $post) {
            return new PostTemplate($post, TRUE);
        }, $posts);
        
        ViewController::sendViewData('posts', $postsTemplate);
    }
    
}
