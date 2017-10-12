<?php
/**
 * TermController.php
 */

namespace SoftnCMS\controllers\theme;

use SoftnCMS\controllers\template\PostTemplate;
use SoftnCMS\controllers\ThemeControllerAbstract;
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\managers\PostsManager;
use SoftnCMS\models\managers\PostsTermsManager;
use SoftnCMS\models\managers\TermsManager;
use SoftnCMS\models\tables\Post;
use SoftnCMS\rute\Router;
use SoftnCMS\util\Util;

/**
 * Class TermController
 * @author Nicolás Marulanda P.
 */
class TermController extends ThemeControllerAbstract {
    
    protected function read($id) {
        $termsManager = new TermsManager();
        $term         = $termsManager->searchById($id);
        
        if (empty($term)) {
            Util::redirect(Router::getSiteURL());
        }
        
        $postStatus        = TRUE;
        $postsManager      = new PostsManager();
        $postsTermsManager = new PostsTermsManager();
        $count             = $postsTermsManager->countPostsByTermIdAndPostStatus($id, $postStatus);
        $limit        = parent::pagination($count);
        
        $posts         = $postsManager->searchAllByTermIdAndStatus($term->getId(), $postStatus, $limit);
        $postsTemplate = array_map(function(Post $post) {
            return new PostTemplate($post, TRUE);
        }, $posts);
        
        ViewController::sendViewData('posts', $postsTemplate);
        ViewController::sendViewData('term', $term);
    }
    
}
