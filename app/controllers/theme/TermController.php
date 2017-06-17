<?php
/**
 * TermController.php
 */

namespace SoftnCMS\controllers\theme;

use SoftnCMS\controllers\template\PostTemplate;
use SoftnCMS\controllers\ThemeControllerAbstract;
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\managers\OptionsManager;
use SoftnCMS\models\managers\PostsManager;
use SoftnCMS\models\managers\TermsManager;
use SoftnCMS\models\tables\Post;
use SoftnCMS\util\Escape;
use SoftnCMS\util\Util;

/**
 * Class TermController
 * @author Nicolás Marulanda P.
 */
class TermController extends ThemeControllerAbstract {
    
    protected function read($id) {
        $termsManager = new TermsManager();
        $term          = $termsManager->searchById($id);
        
        if (empty($term)) {
            $optionsManager = new OptionsManager();
            Util::redirect($optionsManager->getSiteUrl());
        }
        
        $count        = $term->getTermPostCount();
        $postsManager = new PostsManager();
        $pagination   = parent::pagination($count);
        $filters      = [];
        
        if ($pagination !== FALSE) {
            $filters['limit'] = $pagination;
        }
        
        $posts = $postsManager->searchByTermId($term->getId(), $filters);
        array_walk($posts, function(Post $post) {
            $post->setPostContents(Escape::htmlDecode($post->getPostContents()));
        });
        
        $postsTemplate = array_map(function(Post $post) {
            return new PostTemplate($post, TRUE);
        }, $posts);
        
        ViewController::sendViewData('posts', $postsTemplate);
        ViewController::sendViewData('term', $term);
    }
    
}
