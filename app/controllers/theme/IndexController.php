<?php
/**
 * IndexController.php
 */

namespace SoftnCMS\controllers\theme;

use SoftnCMS\controllers\template\PostTemplate;
use SoftnCMS\models\managers\PostsManager;
use SoftnCMS\models\tables\Post;
use SoftnCMS\util\controller\ThemeControllerAbstract;

/**
 * Class IndexController
 * @author Nicolás Marulanda P.
 */
class IndexController extends ThemeControllerAbstract {
    
    public function index() {
        $postStatus    = TRUE;
        $postsManager  = new PostsManager();
        $count         = $postsManager->countByStatus($postStatus);
        $posts         = $postsManager->searchAllByStatus($postStatus, $this->rowsPages($count));
        $postsTemplate = array_map(function(Post $post) {
            return new PostTemplate($post, TRUE);
        }, $posts);
        $this->sendDataView(['posts' => $postsTemplate]);
        $this->view();
    }
    
}
