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
use SoftnCMS\util\Escape;
use SoftnCMS\util\Util;

/**
 * Class UserController
 * @author Nicolás Marulanda P.
 */
class UserController extends ThemeControllerAbstract {
    
    protected function read($id) {
        $usersManager = new UsersManager();
        $user          = $usersManager->searchById($id);
        
        if (empty($user)) {
            $optionsManager = new OptionsManager();
            Util::redirect($optionsManager->getSiteUrl());
        }
        
        $count        = $user->getUserPostcount();
        $postsManager = new PostsManager();
        $pagination   = parent::pagination($count);
        $filters      = [];
        
        if ($pagination !== FALSE) {
            $filters['limit'] = $pagination;
        }
        
        $posts = $postsManager->searchByUserId($user->getId(), $filters);
        array_walk($posts, function(Post $post) {
            $post->setPostContents(Escape::htmlDecode($post->getPostContents()));
        });
        
        $postsTemplate = array_map(function(Post $post) {
            return new PostTemplate($post, TRUE);
        }, $posts);
        
        ViewController::sendViewData('posts', $postsTemplate);
        ViewController::sendViewData('user', $user);
    }
    
}
