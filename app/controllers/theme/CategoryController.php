<?php
/**
 * CategoryController.php
 */

namespace SoftnCMS\controllers\theme;

use SoftnCMS\controllers\template\PostTemplate;
use SoftnCMS\controllers\ThemeControllerAbstract;
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\managers\CategoriesManager;
use SoftnCMS\models\managers\PostsCategoriesManager;
use SoftnCMS\models\managers\PostsManager;
use SoftnCMS\models\tables\Post;
use SoftnCMS\rute\Router;
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
            Util::redirect(Router::getSiteURL());
        }
        
        $postStatus = TRUE;
        $postsManager = new PostsManager();
        $postsCategoriesManager = new PostsCategoriesManager();
        $count        = $postsCategoriesManager->countPostsByCategoryIdAndPostStatus($id, $postStatus);
        $pagination   = parent::pagination($count);
        $filters      = [];
        
        if ($pagination !== FALSE) {
            $filters['limit'] = $pagination;
        }
        
        $posts = $postsManager->searchAllByCategoryIdAndStatus($category->getId(), $postStatus, $filters);
        $postsTemplate = array_map(function(Post $post) {
            return new PostTemplate($post, TRUE);
        }, $posts);
        
        ViewController::sendViewData('posts', $postsTemplate);
        ViewController::sendViewData('category', $category);
    }
    
}
