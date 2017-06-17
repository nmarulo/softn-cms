<?php
/**
 * CategoryController.php
 */

namespace SoftnCMS\controllers\theme;

use SoftnCMS\controllers\template\PostTemplate;
use SoftnCMS\controllers\ThemeControllerAbstract;
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\managers\CategoriesManager;
use SoftnCMS\models\managers\OptionsManager;
use SoftnCMS\models\managers\PostsManager;
use SoftnCMS\models\tables\Post;
use SoftnCMS\util\Escape;
use SoftnCMS\util\Util;

/**
 * Class CategoryController
 * @author Nicolás Marulanda P.
 */
class CategoryController extends ThemeControllerAbstract {
    
    protected function read($id) {
        $categoriesManager = new CategoriesManager();
        $category          = $categoriesManager->searchById($id);
        
        if (empty($category)) {
            $optionsManager = new OptionsManager();
            Util::redirect($optionsManager->getSiteUrl());
        }
        
        $count        = $category->getCategoryPostCount();
        $postsManager = new PostsManager();
        $pagination   = parent::pagination($count);
        $filters      = [];
        
        if ($pagination !== FALSE) {
            $filters['limit'] = $pagination;
        }
        
        $posts = $postsManager->searchByCategoryId($category->getId(), $filters);
        array_walk($posts, function(Post $post) {
            $post->setPostContents(Escape::htmlDecode($post->getPostContents()));
        });
        
        $postsTemplate = array_map(function(Post $post) {
            return new PostTemplate($post, TRUE);
        }, $posts);
        
        ViewController::sendViewData('posts', $postsTemplate);
        ViewController::sendViewData('category', $category);
    }
    
}
