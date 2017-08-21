<?php
/**
 * UserController.php
 */

namespace SoftnCMS\controllers\theme;

use SoftnCMS\controllers\template\PostTemplate;
use SoftnCMS\controllers\ThemeControllerAbstract;
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\managers\OptionsManager;
use SoftnCMS\models\managers\PostsManager;
use SoftnCMS\models\managers\UsersManager;
use SoftnCMS\models\tables\Post;
use SoftnCMS\rute\Router;
use SoftnCMS\util\Escape;
use SoftnCMS\util\Util;

/**
 * Class UserController
 * @author Nicolás Marulanda P.
 */
class UserController extends ThemeControllerAbstract {
    
    protected function read($id) {
        $usersManager = new UsersManager();
        $user         = $usersManager->searchById($id);
        
        if (empty($user)) {
            Util::redirect(Router::getSiteURL());
        }
        
        $postStatus   = TRUE;
        $postsManager = new PostsManager();
        $count        = $postsManager->countByUserIdAndStatus($id, $postStatus);
        $pagination   = parent::pagination($count);
        $filters      = [];
        
        if ($pagination !== FALSE) {
            $filters['limit'] = $pagination;
        }
        
        $posts         = $postsManager->searchByUserIdAndStatus($user->getId(), $postStatus, $filters);
        $postsTemplate = array_map(function(Post $post) {
            return new PostTemplate($post, TRUE);
        }, $posts);
        
        ViewController::sendViewData('posts', $postsTemplate);
        ViewController::sendViewData('user', $user);
    }
    
}
