<?php
/**
 * CategoryController.php
 */

namespace SoftnCMS\controllers\theme;

use SoftnCMS\models\template\PostTemplate;
use SoftnCMS\models\managers\CategoriesManager;
use SoftnCMS\models\managers\PostsCategoriesManager;
use SoftnCMS\models\managers\PostsManager;
use SoftnCMS\models\tables\Post;
use SoftnCMS\util\controller\ThemeControllerAbstract;

/**
 * Class CategoryController
 * @author Nicolás Marulanda P.
 */
class CategoryController extends ThemeControllerAbstract {
    
    public function index($id) {
        $categoriesManager = new CategoriesManager($this->getConnectionDB());
        $category          = $categoriesManager->searchById($id);
        
        if (empty($category)) {
            $this->redirect();
        }
        
        $postStatus             = TRUE;
        $postsManager           = new PostsManager($this->getConnectionDB());
        $postsCategoriesManager = new PostsCategoriesManager($this->getConnectionDB());
        $count                  = $postsCategoriesManager->countPostsByCategoryIdAndPostStatus($id, $postStatus);
        $limit                  = $this->rowsPages($count);
        $posts                  = $postsManager->searchAllByCategoryIdAndStatus($category->getId(), $postStatus, $limit);
        $postsTemplate          = array_map(function(Post $post) {
            return new PostTemplate($post, TRUE, $this->getRequest()
                                                      ->getSiteUrl(), $this->getConnectionDB());
        }, $posts);
        
        $this->sendDataView([
            'posts'    => $postsTemplate,
            'category' => $category,
        ]);
        $this->view();
    }
    
}
