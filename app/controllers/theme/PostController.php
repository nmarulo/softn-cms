<?php
/**
 * PostController.php
 */

namespace SoftnCMS\controllers\theme;

use SoftnCMS\controllers\ControllerAbstract;
use SoftnCMS\controllers\template\PostTemplate;
use SoftnCMS\controllers\ThemeControllerAbstract;
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\managers\OptionsManager;
use SoftnCMS\models\managers\PostsManager;
use SoftnCMS\util\Escape;
use SoftnCMS\util\Util;

/**
 * Class PostController
 * @author Nicolás Marulanda P.
 */
class PostController extends ThemeControllerAbstract {
    
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
