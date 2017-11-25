<?php
/**
 * UserController.php
 */

namespace SoftnCMS\controllers\theme;

use SoftnCMS\models\template\PostTemplate;
use SoftnCMS\models\managers\PostsManager;
use SoftnCMS\models\managers\UsersManager;
use SoftnCMS\models\tables\Post;
use SoftnCMS\util\controller\ThemeControllerAbstract;

/**
 * Class UserController
 * @author Nicolás Marulanda P.
 */
class UserController extends ThemeControllerAbstract {
    
    public function index($id) {
        $usersManager = new UsersManager($this->getConnectionDB());
        $user         = $usersManager->searchById($id);
        
        if (empty($user)) {
            $this->redirect();
        }
        
        $postStatus    = TRUE;
        $postsManager  = new PostsManager($this->getConnectionDB());
        $count         = $postsManager->countByUserIdAndStatus($id, $postStatus);
        $limit         = $this->rowsPages($count);
        $posts         = $postsManager->searchByUserIdAndStatus($user->getId(), $postStatus, $limit);
        $postsTemplate = array_map(function(Post $post) {
            return new PostTemplate($post, TRUE);
        }, $posts);
        
        $this->sendDataView([
            'posts' => $postsTemplate,
            'user'  => $user,
        ]);
        $this->view();
    }
    
}
